<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            /* =======================
             *  COUNTS
             * ======================= */
            $stats = [
                'tickets_total'      => Ticket::count(),
                'tickets_open'       => Ticket::where('status', 'open')->count(),
                'tickets_in_progress' => Ticket::where('status', 'in_progress')->count(),
                'tickets_resolved'   => Ticket::where('status', 'resolved')->count(),
                'tickets_closed'     => Ticket::where('status', 'closed')->count(),
                'companies_active'   => Company::where('is_active', 'active')->count(),
                'ticket_types'       => TicketType::count(),
            ];

            /* =======================
             *  STATUS CHART
             * ======================= */
            $ticketsByStatus = Ticket::select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status');

            /* =======================
             *  PRIORITY CHART
             * ======================= */
            $ticketsByPriority = Ticket::select('priority', DB::raw('COUNT(*) as total'))
                ->groupBy('priority')
                ->pluck('total', 'priority');

            /* =======================
             *  TICKET TYPE CHART
             * ======================= */
            $ticketsByType = Ticket::join('ticket_types', 'tickets.ticket_type_id', '=', 'ticket_types.id')
                ->select('ticket_types.name', DB::raw('COUNT(*) as total'))
                ->groupBy('ticket_types.name')
                ->pluck('total', 'name');

            /* =======================
             *  COMPANY LOAD
             * ======================= */
            $ticketsByCompany = Ticket::join('companies', 'tickets.company_id', '=', 'companies.id')
                ->select('companies.name', DB::raw('COUNT(*) as total'))
                ->groupBy('companies.name')
                ->orderByDesc('total')
                ->limit(5)
                ->pluck('total', 'name');

            /* =======================
             *  RECENT TICKETS
             * ======================= */
            $recentTickets = Ticket::with(['company', 'ticketType', 'user'])
                ->latest()
                ->limit(10)
                ->get();

            /* =======================
             *  OVERDUE (OPEN > 48h)
             * ======================= */
            $overdueTickets = Ticket::where('status', 'open')
                ->where('created_at', '<', now()->subHours(48))
                ->count();

            return view('dashboard.index', compact(
                'stats',
                'ticketsByStatus',
                'ticketsByPriority',
                'ticketsByType',
                'ticketsByCompany',
                'recentTickets',
                'overdueTickets'
            ));
        } catch (\Throwable $e) {
            Log::error('Dashboard Error: ' . $e->getMessage());
            abort(500);
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
        //
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
}
