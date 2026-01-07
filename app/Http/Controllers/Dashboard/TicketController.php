<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view ticket');
        try {
            $tickets = Ticket::with('ticketType')->orderBy('created_at', 'desc')
            ->get();
            return view('dashboard.tickets.index', compact('tickets'));
        } catch (\Throwable $th) {
            Log::error("Ticket Index Failed:" . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize('view ticket');
        try {
            $ticket = Ticket::with('ticketComments', 'ticketAttachments', 'company', 'ticketType')->findOrFail($id);
            return view('dashboard.tickets.show', compact('ticket'));
        } catch (\Throwable $th) {
            Log::error("Ticket Show Failed:" . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateStatus(Request $request, $id)
    {
        $this->authorize('update ticket');
        try {
            $ticket = Ticket::findOrFail($id);
            $ticket->status = $request->status;
            $ticket->save();

            return redirect()->back()->with('success', 'Ticket status updated successfully!');
        } catch (\Throwable $th) {
            Log::error("Ticket Status Update Failed:" . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    public function createComment(string $id)
    {
        $this->authorize('create ticket');
        try {
            $ticket = Ticket::findOrFail($id);
            return view('dashboard.tickets.create-comment', compact('ticket'));
        } catch (\Throwable $th) {
            Log::error("Ticket Comment Create Failed:" . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    public function storeComment(Request $request, string $id)
    {
        $this->authorize('create ticket');
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            $ticket = Ticket::findOrFail($id);

            $ticketComment = new TicketComment();
            $ticketComment->user_id = auth()->user()->id;
            $ticketComment->comment = $request->comment;
            $ticketComment->company_id = null;
            $ticketComment->ticket_id = $ticket->id;
            $ticketComment->save();

            return redirect()->route('dashboard.tickets.show', $ticket->id)->with('success', 'Comment added successfully!');
        } catch (\Throwable $th) {
            Log::error("Ticket Comment Store Failed:" . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }
}
