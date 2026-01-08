@extends('layouts.master')

@section('title', 'Dashboard')

@section('css')
    <style>
        .kpi-card {
            border-left: 5px solid;
        }

        .kpi-icon {
            font-size: 34px;
            opacity: 0.85;
        }

        .kpi-danger {
            border-color: #dc3545
        }

        .kpi-warning {
            border-color: #ffc107
        }

        .kpi-success {
            border-color: #198754
        }

        .kpi-primary {
            border-color: #0d6efd
        }

        .badge-open {
            background: #dc3545
        }

        .badge-progress {
            background: #ffc107;
            color: #000
        }

        .badge-closed {
            background: #198754
        }

        .card-header-clean {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
        }
    </style>
@endsection


@section('content')

    {{-- ALERTS --}}
    @if ($stats['tickets_open'] > 0)
        <div class="alert alert-danger d-flex align-items-center mb-4">
            <i class="ti ti-alert-circle me-2"></i>
            <strong>{{ $stats['tickets_open'] }}</strong>&nbsp;Open tickets pending
        </div>
    @endif

    @if ($overdueTickets > 0)
        <div class="alert alert-warning d-flex align-items-center mb-4">
            <i class="ti ti-clock me-2"></i>
            <strong>{{ $overdueTickets }}</strong>&nbsp;Tickets overdue (48+ hours)
        </div>
    @endif

    {{-- KPI CARDS --}}
    <div class="row g-4 mb-5">

        <div class="col-md-3">
            <div class="card kpi-card kpi-primary shadow-sm">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <small>Total Tickets</small>
                        <h2>{{ $stats['tickets_total'] }}</h2>
                    </div>
                    <i class="ti ti-ticket kpi-icon text-primary"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card kpi-card kpi-danger shadow-sm">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <small>Open</small>
                        <h2>{{ $stats['tickets_open'] }}</h2>
                    </div>
                    <i class="ti ti-alert-circle kpi-icon text-danger"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card kpi-card kpi-warning shadow-sm">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <small>In Progress</small>
                        <h2>{{ $stats['tickets_in_progress'] }}</h2>
                    </div>
                    <i class="ti ti-loader kpi-icon text-warning"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card kpi-card kpi-success shadow-sm">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <small>Resolved / Closed</small>
                        <h2>{{ $stats['tickets_resolved'] + $stats['tickets_closed'] }}</h2>
                    </div>
                    <i class="ti ti-circle-check kpi-icon text-success"></i>
                </div>
            </div>
        </div>

    </div>


    {{-- CHARTS --}}
    <div class="row g-4">

        <div class="col-md-4">
            <div class="card p-3">
                <div class="card-header-clean">
                    <span><i class="ti ti-chart-donut me-1"></i> Status</span>
                </div>
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3" style="height: 330px">
                <div class="card-header-clean">
                    <span><i class="ti ti-chart-bar me-1"></i> Priority</span>
                </div>
                <canvas id="priorityChart"></canvas>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <div class="card-header-clean">
                    <span><i class="ti ti-category me-1"></i> Ticket Types</span>
                </div>
                <canvas id="typeChart"></canvas>
            </div>
        </div>

    </div>

    {{-- COMPANY LOAD --}}
    <div class="card mt-5 p-3">
        <div class="card-header-clean mb-2">
            <span><i class="ti ti-building me-1"></i> Top Companies</span>
        </div>
        <canvas id="companyChart"></canvas>
    </div>


    {{-- RECENT TICKETS --}}
    <div class="card mt-5">
        <div class="card-body">
            <h5><i class="ti ti-clock me-1"></i> Recent Tickets</h5>

            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Company</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentTickets as $ticket)
                        <tr>
                            <td>{{ $ticket->title }}</td>
                            <td>{{ $ticket->company->name ?? '-' }}</td>
                            <td>{{ $ticket->ticketType->name ?? '-' }}</td>
                            <td>
                                <span
                                    class="badge
                            {{ $ticket->status == 'open'
                                ? 'badge-open'
                                : ($ticket->status == 'in_progress'
                                    ? 'badge-progress'
                                    : 'badge-closed') }}">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($ticket->priority) }}</span>
                            </td>
                            <td>{{ $ticket->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(statusChart, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($ticketsByStatus->keys()) !!},
                datasets: [{
                    data: {!! json_encode($ticketsByStatus->values()) !!}
                }]
            }
        });

        new Chart(priorityChart, {
            type: 'bar',
            data: {
                labels: {!! json_encode($ticketsByPriority->keys()) !!},
                datasets: [{
                    data: {!! json_encode($ticketsByPriority->values()) !!}
                }]
            }
        });

        new Chart(typeChart, {
            type: 'pie',
            data: {
                labels: {!! json_encode($ticketsByType->keys()) !!},
                datasets: [{
                    data: {!! json_encode($ticketsByType->values()) !!}
                }]
            }
        });

        new Chart(companyChart, {
            type: 'bar',
            data: {
                labels: {!! json_encode($ticketsByCompany->keys()) !!},
                datasets: [{
                    data: {!! json_encode($ticketsByCompany->values()) !!}
                }]
            }
        });
    </script>
@endsection
