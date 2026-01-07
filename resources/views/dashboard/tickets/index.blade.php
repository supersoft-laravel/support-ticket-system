@extends('layouts.master')

@section('title', __('Tickets'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Tickets') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Tickets List Table -->
        <div class="card">
            <div class="card-header">
                {{-- @canany(['create ticket type'])
                    <a href="{{ route('dashboard.ticket-types.create') }}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Ticket Type') }}</span>
                    </a>
                @endcan --}}
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Priority') }}</th>
                            <th>{{ __('Status') }}</th>
                            @canany(['delete ticket', 'update ticket', 'view ticket'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $index => $ticket)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $ticket->title }}</td>
                                <td>{{ $ticket->ticketType->name }}</td>
                                <td>{{ ucfirst($ticket->priority) }}</td>

                                <td>
                                    @php
                                        $statusColors = [
                                            'open' => 'warning',
                                            'in_progress' => 'info',
                                            'resolved' => 'success',
                                            'closed' => 'secondary',
                                        ];
                                    @endphp

                                    <span class="badge me-4 bg-label-{{ $statusColors[$ticket->status] ?? 'secondary' }}">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </td>

                                @canany(['delete ticket', 'update ticket', 'view ticket'])
                                    <td class="d-flex">
                                        @canany(['delete ticket'])
                                            <form action="{{ route('dashboard.tickets.destroy', $ticket->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Ticket') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update ticket'])
                                            <span class="text-nowrap">
                                                <a href="javascript:void(0)"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1 edit-ticket-btn"
                                                    data-bs-toggle="modal" data-bs-target="#editTicketModal"
                                                    data-id="{{ $ticket->id }}" data-status="{{ $ticket->status }}"
                                                    title="{{ __('Edit Ticket Status') }}">
                                                    <i class="ti ti-edit ti-md"></i>
                                                </a>
                                            </span>
                                        @endcan
                                        @canany(['view ticket'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.tickets.show', $ticket->id) }}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1 edit-ticket-btn"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Ticket Comments') }}">
                                                    <i class="ti ti-edit ti-md"></i>
                                                </a>
                                            </span>
                                        @endcan
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Ticket Modal -->
    <div class="modal fade" id="editTicketModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-simple modal-edit-user">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-6">
                        <h4 class="mb-2">Edit Ticket Status</h4>
                        {{-- <p>Updating user details will receive a privacy audit.</p> --}}
                    </div>
                    <form id="editTicketForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="ticket_id" id="ticket_id">

                        <div class="mb-3">
                            <label for="status" class="form-label">{{ __('Ticket Status') }}</label>
                            <select name="status" id="status" class="form-select select2" required>
                                <option value="open">{{ __('Open') }}</option>
                                <option value="in_progress">{{ __('In Progress') }}</option>
                                <option value="resolved">{{ __('Resolved') }}</option>
                                <option value="closed">{{ __('Closed') }}</option>
                            </select>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-3">Submit</button>
                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--/ Edit Order Modal -->


@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.edit-ticket-btn').on('click', function() {
                let ticketId = $(this).data('id');
                let status = $(this).data('status');

                // Set hidden input
                $('#ticket_id').val(ticketId);
                // Set dropdown selected value
                $('#status').val(status);

                // Build form action URL using route name
                let actionUrl = "{{ route('dashboard.tickets.status.update', ':id') }}";
                actionUrl = actionUrl.replace(':id', ticketId);

                $('#editTicketForm').attr('action', actionUrl);
            });
        });
    </script>
@endsection
