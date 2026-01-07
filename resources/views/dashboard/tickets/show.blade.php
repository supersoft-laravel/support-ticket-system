@extends('layouts.master')

@section('title', __('Ticket Details'))

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.tickets.index') }}">{{ __('Tickets') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Details') }}</li>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-4">

        <!-- Ticket Info -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="mb-1">{{ $ticket->title }}</h4>
                            <p class="text-muted mb-0">
                                {{ $ticket->company?->name }} ·
                                {{ $ticket->ticketType?->name }}
                            </p>
                        </div>

                        <span class="badge bg-label-primary">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </div>

                    <hr>

                    <p>{{ $ticket->description }}</p>

                    <div class="d-flex gap-3">
                        <span class="badge bg-label-success">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                        <span class="text-muted">
                            Created {{ $ticket->created_at->diffForHumans() }}
                        </span>
                    </div>

                </div>
            </div>
        </div>

        <!-- Attachments -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">📎 Attachments</h5>

                    @if ($ticket->ticketAttachments->count())
                        <ul class="list-group list-group-flush">
                            @foreach ($ticket->ticketAttachments as $file)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        {{ $file->file_name ?? 'Attachment' }}
                                        <small class="text-muted d-block">
                                            {{ $file->file_type }} · {{ $file->file_size }} KB
                                        </small>
                                    </div>
                                    <a href="{{ asset('storage/'.$file->file_path) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">No attachments available.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Comments Table -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">💬 Comments</h5>

                        <!-- Create Comment Button -->
                        <a href="{{ route('dashboard.ticket-comments.create', $ticket->id) }}"
                           class="btn btn-primary btn-sm">
                            + Create Comment
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Author</th>
                                    <th>Comment</th>
                                    <th>Attachments</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ticket->ticketComments as $index => $comment)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>

                                        <td>
                                            {{ $comment->user?->name
                                                ?? $comment->company?->name
                                                ?? 'System' }}
                                        </td>

                                        <td style="max-width: 400px;">
                                            {{ $comment->comment }}
                                        </td>

                                        <td>
                                            @if ($comment->attachments)
                                                <span class="badge bg-label-info">
                                                    Yes
                                                </span>
                                            @else
                                                <span class="badge bg-label-secondary">
                                                    No
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            {{ $comment->created_at->format('d M Y, h:i A') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            No comments found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
