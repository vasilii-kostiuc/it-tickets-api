<?php

namespace App\Http\Controllers\Api\V1\Asterisk;

use App\Domain\Call\PhoneFormatters\PhoneFormatterInterface;
use App\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Domain\Client\Services\ClientService;
use App\Domain\Ticket\Services\TicketService;
use App\Domain\User\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Asterisk\AsteriskCallRequest;
use App\Http\Resources\ApiResponseResource;
use App\Http\Resources\Ticket\TicketResource;

class AsteriskController extends Controller
{
    private ClientService $clientService;
    private UserService $userService;
    private TicketService $ticketService;
    private PhoneFormatterInterface $phoneFormatter;

    public function __construct(ClientService $clientService, UserService $userService, TicketService $ticketService, PhoneFormatterInterface $phoneFormatter)
    {
        $this->clientService = $clientService;
        $this->userService = $userService;
        $this->ticketService = $ticketService;
        $this->phoneFormatter = $phoneFormatter;
    }

    public function call(AsteriskCallRequest $request){
        $phone =  $this->phoneFormatter->format($request->get('phone'));
        $extension = $request->get('extension');

        $client = $this->clientService->getOrNewClientForPhone($phone);

        $user = $this->userService->getOperatorByExtension($extension);

        $data = $request->safe()->all();

        $ticket = $this->ticketService->createTicket($client, $user, $data);

        return ApiResponseResource::success(new TicketResource($ticket), 'Ticket created successfully');
    }
}
