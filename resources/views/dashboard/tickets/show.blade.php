@extends('layouts.master')

@section('title', __('Ticket Details'))

@section('css')
    <style>
        .avatar {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .attachment-card {
            transition: all 0.2s ease;
            background: var(--bs-light);
        }

        .attachment-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background: var(--bs-white);
        }

        .comment-section {
            scrollbar-width: thin;
            scrollbar-color: var(--bs-secondary) var(--bs-light);
        }

        .comment-section::-webkit-scrollbar {
            width: 6px;
        }

        .comment-section::-webkit-scrollbar-track {
            background: var(--bs-light);
        }

        .comment-section::-webkit-scrollbar-thumb {
            background-color: var(--bs-secondary);
            border-radius: 3px;
        }

        .file-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(var(--bs-primary-rgb), 0.1);
            border-radius: 8px;
        }

        .form-section {
            border: 1px solid var(--bs-border-color);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            height: 100%;
        }

        .preview-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            background: var(--bs-light);
            border-radius: 4px;
            margin-bottom: 0.5rem;
        }

        .preview-item i {
            color: var(--bs-primary);
        }

        .attachment-thumbnail {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid var(--bs-border-color);
        }
    </style>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.tickets.index') }}">{{ __('Tickets') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Details') }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4">

            <!-- Ticket Header -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <h4 class="mb-0">{{ $ticket->title }}</h4>
                                    <span
                                        class="badge bg-label-{{ $ticket->priority === 'high' ? 'danger' : ($ticket->priority === 'medium' ? 'warning' : 'info') }}">
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                </div>
                                <div class="text-muted">
                                    <span class="d-inline-flex align-items-center gap-1">
                                        <i class="ti ti-building"></i>
                                        {{ $ticket->company?->name ?? 'No Company' }}
                                    </span>
                                    <span class="mx-2">•</span>
                                    <span class="d-inline-flex align-items-center gap-1">
                                        <i class="ti ti-category"></i>
                                        {{ $ticket->ticketType?->name ?? 'Uncategorized' }}
                                    </span>
                                </div>
                            </div>

                            <div class="text-end">
                                <span
                                    class="badge bg-label-{{ $ticket->status === 'open' ? 'success' : ($ticket->status === 'closed' ? 'secondary' : 'warning') }} fs-6">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                                <p class="text-muted mt-1 mb-0">
                                    <small>
                                        <i class="ti ti-clock"></i>
                                        {{ $ticket->created_at->diffForHumans() }}
                                    </small>
                                </p>
                            </div>
                        </div>

                        <hr>

                        <!-- Description -->
                        <div class="ticket-description mb-4">
                            <h6 class="text-muted mb-2">Description</h6>
                            <div class="bg-light rounded p-3">
                                <p class="mb-0">{{ $ticket->description }}</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Attachments Section -->
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ti ti-paperclip fs-5"></i>
                            <h5 class="mb-0">Ticket Attachments</h5>
                            @if ($ticket->ticketAttachments->count())
                                <span class="badge bg-primary rounded-pill">
                                    {{ $ticket->ticketAttachments->count() }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($ticket->ticketAttachments->count())
                            <div class="row g-3">
                                @foreach ($ticket->ticketAttachments as $file)
                                    <div class="col-12">
                                        <div class="attachment-card border rounded p-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="file-icon">
                                                        @if (str_contains($file->file_type, 'image'))
                                                            <i class="ti ti-photo text-primary fs-2"></i>
                                                        @elseif(str_contains($file->file_type, 'pdf'))
                                                            <i class="ti ti-file-text text-danger fs-2"></i>
                                                        @elseif(str_contains($file->file_type, 'word') || str_contains($file->file_type, 'document'))
                                                            <i class="ti ti-file-word text-primary fs-2"></i>
                                                        @elseif(str_contains($file->file_type, 'excel') || str_contains($file->file_type, 'sheet'))
                                                            <i class="ti ti-file-spreadsheet text-success fs-2"></i>
                                                        @else
                                                            <i class="ti ti-file text-secondary fs-2"></i>
                                                        @endif
                                                    </div>
                                                    <div class="file-info">
                                                        <h6 class="mb-1 text-truncate" style="max-width: 200px;">
                                                            {{ $file->file_name ?? 'Attachment' }}
                                                        </h6>
                                                        <div class="text-muted small">
                                                            <span>{{ strtoupper($file->file_extension ?? 'FILE') }}</span>
                                                            <span class="mx-1">•</span>
                                                            <span>{{ number_format($file->file_size / 1024, 1) }} KB</span>
                                                            @if($file->note)
                                                                <div class="mt-1">
                                                                    <small><i class="ti ti-note"></i> {{ $file->note }}</small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                                        class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                        data-bs-title="View">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                    <a href="{{ asset('storage/' . $file->file_path) }}"
                                                        download="{{ $file->file_name }}"
                                                        class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip"
                                                        data-bs-title="Download">
                                                        <i class="ti ti-download"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="ti ti-paperclip text-muted display-5"></i>
                                </div>
                                <p class="text-muted mb-0">No attachments available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Comments Section - Chat Style -->
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ti ti-message-dots fs-5"></i>
                            <h5 class="mb-0">Conversation</h5>
                            @if ($ticket->ticketComments->count())
                                <span class="badge bg-primary rounded-pill">
                                    {{ $ticket->ticketComments->count() }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="comment-section" style="max-height: 500px; overflow-y: auto;">
                            @if ($ticket->ticketComments->count())
                                <div class="p-4">
                                    @foreach ($ticket->ticketComments as $comment)
                                        <!-- Support Message (company_id is null) -->
                                        @if (is_null($comment->company_id))
                                            <div class="d-flex mb-4">
                                                <div class="flex-shrink-0">
                                                    <div
                                                        class="avatar avatar-sm bg-label-success rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="ti ti-user-check fs-5"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <div class="bg-light rounded p-3">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <div>
                                                                <strong class="text-success">
                                                                    {{ $comment->user?->name ?? 'Support Agent' }}
                                                                </strong>
                                                                <span class="text-muted small ms-2">
                                                                    <i class="ti ti-badge-check"></i> Support Team
                                                                </span>
                                                                @if($comment->is_internal)
                                                                    <span class="badge bg-warning text-dark small ms-2">
                                                                        Internal
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <span class="text-muted small">
                                                                {{ $comment->created_at->format('h:i A') }}
                                                            </span>
                                                        </div>
                                                        <p class="mb-2">{{ $comment->comment }}</p>

                                                        <!-- Comment Attachments -->
                                                        @if ($comment->attachments && count($comment->attachments) > 0)
                                                            <div class="mt-2">
                                                                <h6 class="text-muted small mb-2">
                                                                    <i class="ti ti-paperclip"></i> Attachments:
                                                                </h6>
                                                                <div class="d-flex flex-wrap gap-2">
                                                                    @foreach ($comment->attachments as $attachment)
                                                                        <a href="{{ $attachment['url'] ?? '#' }}"
                                                                            target="_blank"
                                                                            class="badge bg-label-primary text-decoration-none d-flex align-items-center gap-1">
                                                                            <i class="ti ti-file"></i>
                                                                            {{ $attachment['name'] ?? 'Attachment' }}
                                                                        </a>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <div class="text-muted small mt-2">
                                                            {{ $comment->created_at->format('M d, Y') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Company Message (user_id is null) -->
                                        @elseif(is_null($comment->user_id))
                                            <div class="d-flex mb-4">
                                                <div class="flex-grow-1 me-3 text-end">
                                                    <div class="bg-primary text-white rounded p-3">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-2">
                                                            <span class="text-white-50 small">
                                                                {{ $comment->created_at->format('h:i A') }}
                                                            </span>
                                                            <div>
                                                                <span class="text-white-50 small me-2">
                                                                    <i class="ti ti-building"></i> Company
                                                                </span>
                                                                <strong>
                                                                    {{ $comment->company?->name ?? 'Company User' }}
                                                                </strong>
                                                            </div>
                                                        </div>
                                                        <p class="mb-2">{{ $comment->comment }}</p>

                                                        <!-- Comment Attachments -->
                                                        @if ($comment->attachments && count($comment->attachments) > 0)
                                                            <div class="mt-2">
                                                                <h6 class="text-white-50 small mb-2 text-end">
                                                                    <i class="ti ti-paperclip"></i> Attachments:
                                                                </h6>
                                                                <div class="d-flex flex-wrap gap-2 justify-content-end">
                                                                    @foreach ($comment->attachments as $attachment)
                                                                        <a href="{{ $attachment['url'] ?? '#' }}"
                                                                            target="_blank"
                                                                            class="badge bg-white text-primary text-decoration-none d-flex align-items-center gap-1">
                                                                            <i class="ti ti-file"></i>
                                                                            {{ $attachment['name'] ?? 'Attachment' }}
                                                                        </a>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <div class="text-white-50 small mt-2 text-end">
                                                            {{ $comment->created_at->format('M d, Y') }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div
                                                        class="avatar avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="ti ti-building fs-5 text-white"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Regular User Message (both IDs present) -->
                                        @else
                                            <div class="d-flex mb-4">
                                                <div class="flex-shrink-0">
                                                    <div
                                                        class="avatar avatar-sm bg-label-info rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="ti ti-user fs-5"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <div class="bg-light rounded p-3">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-2">
                                                            <div>
                                                                <strong>
                                                                    {{ $comment->user?->name ?? 'User' }}
                                                                </strong>
                                                                @if ($comment->company)
                                                                    <span class="text-muted small ms-2">
                                                                        ({{ $comment->company->name }})
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <span class="text-muted small">
                                                                {{ $comment->created_at->format('h:i A') }}
                                                            </span>
                                                        </div>
                                                        <p class="mb-2">{{ $comment->comment }}</p>

                                                        <!-- Comment Attachments -->
                                                        @if ($comment->attachments && count($comment->attachments) > 0)
                                                            <div class="mt-2">
                                                                <h6 class="text-muted small mb-2">
                                                                    <i class="ti ti-paperclip"></i> Attachments:
                                                                </h6>
                                                                <div class="d-flex flex-wrap gap-2">
                                                                    @foreach ($comment->attachments as $attachment)
                                                                        <a href="{{ $attachment['url'] ?? '#' }}"
                                                                            target="_blank"
                                                                            class="badge bg-label-primary text-decoration-none d-flex align-items-center gap-1">
                                                                            <i class="ti ti-file"></i>
                                                                            {{ $attachment['name'] ?? 'Attachment' }}
                                                                        </a>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <div class="text-muted small mt-2">
                                                            {{ $comment->created_at->format('M d, Y') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="ti ti-message text-muted display-5"></i>
                                    </div>
                                    <h6 class="text-muted">No comments yet</h6>
                                    <p class="text-muted small">Start the conversation by adding a comment</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($ticket->ticketComments->count())
                        <div class="card-footer border-top">
                            <div class="text-center">
                                <small class="text-muted">
                                    Showing {{ $ticket->ticketComments->count() }} comment(s)
                                </small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>



            <!-- Action Forms Section -->
            <div class="col-12">
                <div class="row g-4">
                    <!-- Add Attachment Form -->
                    <div class="col-12 col-lg-6">
                        <div class="form-section">
                            <h5 class="mb-3">
                                <i class="ti ti-paperclip me-2"></i>Add Attachment
                            </h5>
                            <form action="{{ route('dashboard.ticket-attachments.store', $ticket->id) }}" method="POST"
                                  enctype="multipart/form-data" id="attachmentForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="attachments" class="form-label">Select Files</label>
                                    <input type="file"
                                           class="form-control"
                                           id="attachments"
                                           name="attachments[]"
                                           multiple
                                           accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt">
                                    <div class="form-text">
                                        You can select multiple files. Max size: {{ \App\Helpers\Helper::getMaxUploadSize() }} per file.
                                    </div>
                                </div>

                                <div id="filePreview" class="mb-3" style="display: none;">
                                    <h6 class="text-muted mb-2">Selected Files:</h6>
                                    <div id="previewList"></div>
                                </div>

                                {{-- <div class="mb-3">
                                    <label for="attachment_note" class="form-label">Note (Optional)</label>
                                    <textarea class="form-control"
                                              id="attachment_note"
                                              name="note"
                                              rows="2"
                                              placeholder="Add a note about these files..."></textarea>
                                </div> --}}

                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-outline-secondary" id="clearFiles">
                                        Clear Files
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-upload me-1"></i>Upload Attachments
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Add Comment Form -->
                    <div class="col-12 col-lg-6">
                        <div class="form-section">
                            <h5 class="mb-3">
                                <i class="ti ti-message-plus me-2"></i>Add Comment
                            </h5>
                            <form action="{{ route('dashboard.ticket-comments.store', $ticket->id) }}"
                                  method="POST" id="commentForm">
                                @csrf

                                <div class="mb-3">
                                    <label for="comment" class="form-label">Comment *</label>
                                    <textarea class="form-control"
                                              id="comment"
                                              name="comment"
                                              rows="4"
                                              placeholder="Type your comment here..."
                                              required></textarea>
                                </div>

                                {{-- <div class="mb-3">
                                    <label for="comment_attachments" class="form-label">Add Attachments (Optional)</label>
                                    <input type="file"
                                           class="form-control"
                                           id="comment_attachments"
                                           name="comment_attachments[]"
                                           multiple
                                           accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt">
                                    <div class="form-text">
                                        Attach files to your comment
                                    </div>
                                </div>

                                <div id="commentFilePreview" class="mb-3" style="display: none;">
                                    <h6 class="text-muted mb-2">Selected Files:</h6>
                                    <div id="commentPreviewList"></div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               id="is_internal"
                                               name="is_internal">
                                        <label class="form-check-label" for="is_internal">
                                            Internal Note (Visible only to support team)
                                        </label>
                                    </div>
                                </div> --}}

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-send me-1"></i>Post Comment
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Auto-scroll to bottom of comments
            const commentSection = document.querySelector('.comment-section');
            if (commentSection) {
                commentSection.scrollTop = commentSection.scrollHeight;
            }

            // File preview for attachment form
            const attachmentInput = document.getElementById('attachments');
            const filePreview = document.getElementById('filePreview');
            const previewList = document.getElementById('previewList');
            const clearFilesBtn = document.getElementById('clearFiles');

            attachmentInput.addEventListener('change', function(e) {
                previewList.innerHTML = '';
                const files = Array.from(e.target.files);

                if (files.length > 0) {
                    filePreview.style.display = 'block';

                    files.forEach((file, index) => {
                        const previewItem = document.createElement('div');
                        previewItem.className = 'preview-item';

                        let icon = 'ti-file';
                        if (file.type.startsWith('image/')) icon = 'ti-photo';
                        if (file.type.includes('pdf')) icon = 'ti-file-text';
                        if (file.type.includes('word')) icon = 'ti-file-word';
                        if (file.type.includes('excel') || file.type.includes('spreadsheet')) icon = 'ti-file-spreadsheet';

                        const fileSize = (file.size / 1024).toFixed(1);

                        previewItem.innerHTML = `
                            <i class="ti ${icon}"></i>
                            <span class="text-truncate" style="flex: 1;">${file.name}</span>
                            <small class="text-muted">${fileSize} KB</small>
                        `;

                        previewList.appendChild(previewItem);
                    });
                } else {
                    filePreview.style.display = 'none';
                }
            });

            clearFilesBtn.addEventListener('click', function() {
                attachmentInput.value = '';
                previewList.innerHTML = '';
                filePreview.style.display = 'none';
            });

            // File preview for comment form
            const commentAttachmentInput = document.getElementById('comment_attachments');
            const commentFilePreview = document.getElementById('commentFilePreview');
            const commentPreviewList = document.getElementById('commentPreviewList');

            commentAttachmentInput.addEventListener('change', function(e) {
                commentPreviewList.innerHTML = '';
                const files = Array.from(e.target.files);

                if (files.length > 0) {
                    commentFilePreview.style.display = 'block';

                    files.forEach((file, index) => {
                        const previewItem = document.createElement('div');
                        previewItem.className = 'preview-item';

                        let icon = 'ti-file';
                        if (file.type.startsWith('image/')) icon = 'ti-photo';
                        if (file.type.includes('pdf')) icon = 'ti-file-text';
                        if (file.type.includes('word')) icon = 'ti-file-word';
                        if (file.type.includes('excel') || file.type.includes('spreadsheet')) icon = 'ti-file-spreadsheet';

                        const fileSize = (file.size / 1024).toFixed(1);

                        previewItem.innerHTML = `
                            <i class="ti ${icon}"></i>
                            <span class="text-truncate" style="flex: 1;">${file.name}</span>
                            <small class="text-muted">${fileSize} KB</small>
                        `;

                        commentPreviewList.appendChild(previewItem);
                    });
                } else {
                    commentFilePreview.style.display = 'none';
                }
            });

            // Form validation
            const commentForm = document.getElementById('commentForm');
            const commentTextarea = document.getElementById('comment');

            commentForm.addEventListener('submit', function(e) {
                if (commentTextarea.value.trim() === '') {
                    e.preventDefault();
                    commentTextarea.classList.add('is-invalid');
                    commentTextarea.focus();
                } else {
                    commentTextarea.classList.remove('is-invalid');
                }
            });

            // Add inline validation for comment textarea
            commentTextarea.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    this.classList.remove('is-invalid');
                }
            });
        });
    </script>
@endsection
