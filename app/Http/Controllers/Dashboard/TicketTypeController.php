<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TicketTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view ticket type');
        try {
            $ticketTypes = TicketType::orderBy('created_at', 'desc')
            ->get();
            return view('dashboard.ticket-types.index', compact('ticketTypes'));
        } catch (\Throwable $th) {
            Log::error("Ticket Type Index Failed:" . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create ticket type');
        try {
            return view('dashboard.ticket-types.create');
        } catch (\Throwable $th) {
            Log::error("Ticket Type Create Failed:" . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create ticket type');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:ticket_types,slug',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            $ticketType = new TicketType();
            $ticketType->name = $request->name;
            $ticketType->slug = $request->slug;
            $ticketType->description = $request->description;
            $ticketType->save();

            return redirect()->route('dashboard.ticket-types.index')->with('success', 'Ticket Type created successfully!');
        } catch (\Throwable $th) {
            Log::error("Ticket Type Store Failed:" . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize('view ticket type');
        try {
            $ticketType = TicketType::findOrFail($id);
            return view('dashboard.ticket-types.show', compact('ticketType'));
        } catch (\Throwable $th) {
            Log::error("Ticket Type Show Failed:" . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('update ticket type');
        try {
            $ticketType = TicketType::findOrFail($id);
            return view('dashboard.ticket-types.edit', compact('ticketType'));
        } catch (\Throwable $th) {
            Log::error("Ticket Type Edit Failed:" . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update ticket type');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:ticket_types,slug,' . $id,
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            $ticketType = TicketType::findOrFail($id);
            $ticketType->name = $request->name;
            $ticketType->slug = $request->slug;
            $ticketType->description = $request->description;
            $ticketType->save();

            return redirect()->route('dashboard.ticket-types.index')->with('success', 'Ticket Type updated successfully!');
        } catch (\Throwable $th) {
            Log::error("Ticket Type Update Failed:" . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete ticket type');
        try {
            $ticketType = TicketType::findOrFail($id);
            $ticketType->delete();
            return redirect()->route('dashboard.ticket-types.index')->with('success', 'Ticket Type deleted successfully!');
        } catch (\Throwable $th) {
            Log::error("Ticket Type Delete Failed:" . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    public function updateStatus(string $id)
    {
        $this->authorize('update ticket type');
        try {
            $ticketType = TicketType::findOrFail($id);
            $message = $ticketType->is_active == 'active' ? 'Ticket Type Deactivated Successfully' : 'Ticket Type Activated Successfully';
            if ($ticketType->is_active == 'active') {
                $ticketType->is_active = 'inactive';
                $ticketType->save();
            } else {
                $ticketType->is_active = 'active';
                $ticketType->save();
            }
            return redirect()->back()->with('success', $message);
        } catch (\Throwable $th) {
            Log::error('Ticket Type Status Updation Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
