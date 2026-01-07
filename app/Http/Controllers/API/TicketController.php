<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    public function getTicketTypes(Request $request)
    {
        try {
            $ticketTypes = TicketType::where('is_active', 'active')->get();
            return response()->json([
                'ticket_types' => $ticketTypes,
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('API Ticket Type Home failed', ['error' => $th->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function submitTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'ticket_type_id' => 'required|exists:ticket_types,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {

            $ticket = new Ticket();
            $ticket->title = $request->title;
            $ticket->description = $request->description;
            $ticket->priority = $request->priority;
            $ticket->ticket_type_id = $request->ticket_type_id;
            $ticket->company_id = $request->company->id;
            $ticket->user_id = 2;
            $ticket->save();

            $users = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['superadmin', 'developer']);
            })->get();

            app('notificationService')->notifyUsers(
                $users,
                'Ticket Created',
                'A new ticket has been created and is pending confirmation.',
                'tickets',
                $ticket->id,
                'ticket_details'
            );

            return response()->json([
                'success' => true,
                'message' => 'Ticket created successfully!',
                'ticket' => $ticket,
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error('API Create Ticket failed', ['error' => $th->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCompanyTickets(Request $request)
    {
        try {
            $tickets = Ticket::with('ticketType', 'company')
                ->where('company_id', $request->company->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'tickets' => $tickets,
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('API Get Company Tickets failed', ['error' => $th->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getTicketDetails(Request $request, $ticket_id)
    {
        try {
            $ticket = Ticket::with('ticketComments.user', 'ticketComments.company','ticketAttachments', 'company', 'ticketType')
                ->where('company_id', $request->company->id)
                ->findOrFail($ticket_id);

            return response()->json([
                'ticket' => $ticket,
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('API Get Ticket Details failed', ['error' => $th->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function submitTicketComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticket_id' => 'required|exists:tickets,id',
            'comment' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            Log::info('API Submit Ticket Comment', ['request' => $request->all()]);
            $ticket = Ticket::where('company_id', $request->company->id)
                ->findOrFail($request->ticket_id);

            $ticketComment = new TicketComment();
            $ticketComment->comment = $request->comment;
            $ticketComment->company_id = $request->company->id;
            $ticketComment->ticket_id = $ticket->id;
            $ticketComment->save();

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully!',
                'comment' => $ticketComment,
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error('API Submit Ticket Comment failed', ['error' => $th->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
