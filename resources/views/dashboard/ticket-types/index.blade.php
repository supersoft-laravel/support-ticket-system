@extends('layouts.master')

@section('title', __('Ticket Types'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Ticket Types') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Ticket Types List Table -->
        <div class="card">
            <div class="card-header">
                @canany(['create ticket type'])
                    <a href="{{ route('dashboard.ticket-types.create') }}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Ticket Type') }}</span>
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Status') }}</th>
                            @canany(['delete ticket type', 'update ticket type', 'view ticket type'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ticketTypes as $index => $ticketType)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $ticketType->name }}</td>
                                <td>
                                    <span
                                        class="badge me-4 bg-label-{{ $ticketType->is_active == 'active' ? 'success' : 'danger' }}">{{ ucfirst($ticketType->is_active) }}</span>
                                </td>

                                @canany(['delete ticket type', 'update ticket type', 'view ticket type'])
                                    <td class="d-flex">
                                        @canany(['delete ticket type'])
                                            <form action="{{ route('dashboard.ticket-types.destroy', $ticketType->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Ticket Type') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update ticket type'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.ticket-types.edit', $ticketType->id) }}" class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit Ticket Type') }}">
                                                    <i class="ti ti-edit ti-md"></i>
                                                </a>
                                            </span>
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.ticket-types.status.update', $ticketType->id) }}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ $ticketType->is_active == 'active' ? __('Deactivate Ticket Type') : __('Activate Ticket Type') }}">
                                                    @if ($ticketType->is_active == 'active')
                                                        <i class="ti ti-toggle-right ti-md text-success"></i>
                                                    @else
                                                        <i class="ti ti-toggle-left ti-md text-danger"></i>
                                                    @endif
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

@endsection

@section('script')
    <script>
        //
    </script>
@endsection
