<?php

namespace App\Http\Controllers\Api\V1\Ticket;

use App\Domain\Ticket\Enums\TicketMessageAuthorType;
use App\Domain\Ticket\Models\Ticket;
use App\Domain\Ticket\Models\TicketMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\StoreTicketMessageRequest;
use App\Http\Resources\ApiResponseResource;
use App\Http\Resources\Ticket\TicketMessageResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class TicketMessageController extends Controller
{
    public function index(Request $request, Ticket $ticket): JsonResponse
    {
        $messages = QueryBuilder::for($ticket->messages()->with('media'))
            ->allowedSorts(['created_at'])
            ->defaultSort('created_at')
            ->paginate($request->input('per_page', 20));

        $messages->getCollection()->transform(fn ($message) => new TicketMessageResource($message));

        return ApiResponseResource::paginated($messages);
    }

    public function store(StoreTicketMessageRequest $request, Ticket $ticket): JsonResponse
    {
        $message = $ticket->messages()->create([
            'author_type' => TicketMessageAuthorType::User->value,
            'author_id' => $request->user()->id,
            'body' => $request->validated('body'),
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $message->addMedia($file)
                    ->toMediaCollection('attachments');
            }
        }

        $ticket->update(['last_activity_at' => now()]);

        $message->load('media');

        return ApiResponseResource::success(new TicketMessageResource($message), null, 201);
    }

    public function destroy(Ticket $ticket, TicketMessage $message): JsonResponse
    {
        $message->delete();

        return ApiResponseResource::success();
    }
}
