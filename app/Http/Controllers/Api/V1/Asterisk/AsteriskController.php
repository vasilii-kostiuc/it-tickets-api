<?php

namespace App\Http\Controllers\Api\V1\Asterisk;

use App\Domain\Call\DTO\CallData;
use App\Domain\Call\Enums\CallType;
use App\Domain\Call\PhoneFormatters\PhoneFormatterInterface;
use App\Domain\Call\Services\CallService;
use App\Domain\Client\Services\ClientService;
use App\Domain\Ticket\DTO\TicketCreateData;
use App\Domain\Ticket\Enums\TicketSource;
use App\Domain\Ticket\Services\TicketService;
use App\Domain\User\Services\UserService;
use App\Domain\Utils\Settings\Settings;
use App\Http\Controllers\Controller;
use App\Http\Requests\Asterisk\AsteriskCallRequest;
use App\Http\Resources\ApiResponseResource;
use App\Http\Resources\Ticket\TicketResource;

class AsteriskController extends Controller
{
    private ClientService $clientService;
    private CallService $callService;
    private UserService $userService;
    private TicketService $ticketService;
    private PhoneFormatterInterface $phoneFormatter;

    public function __construct(ClientService $clientService, CallService $callService, UserService $userService, TicketService $ticketService, PhoneFormatterInterface $phoneFormatter)
    {
        $this->clientService = $clientService;
        $this->callService = $callService;
        $this->userService = $userService;
        $this->ticketService = $ticketService;
        $this->phoneFormatter = $phoneFormatter;
    }

    public function call(AsteriskCallRequest $request)
    {
        $phone = $this->phoneFormatter->format($request->get('phone'));
        $extension = $request->get('extension');
        $lang = $request->get('lang');

        $client = $this->clientService->getOrNewClientForPhone($phone);

        $user = $this->userService->getOperatorByExtension($extension);

        if (!$user) {
            return ApiResponseResource::error(message:'Operator not found for the provided extension', statusCode: 404);
        }

        $ticketCreateData = new TicketCreateData(
            clientId: $client->id,
            userId: $user->id,
            source: TicketSource::Phone,
            ticketStatusId: Settings::get('ticket_default_status_id', null),
            slaId: Settings::get('sla_id', null)
        );


        $ticket = $this->ticketService->createTicket($ticketCreateData);

        $callData = CallData::fromTicket($ticket, CallType::In, $extension, null, $lang);

        $this->callService->createCall($callData);

        return ApiResponseResource::success(new TicketResource($ticket), 'Ticket created successfully');
    }
}
