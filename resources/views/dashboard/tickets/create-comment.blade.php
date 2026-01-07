@extends('layouts.master')

@section('title', __('Create Comment'))

@section('css')
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.tickets.index') }}">{{ __('Tickets') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard.tickets.show', $ticket->id) }}">{{ __('Show') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Create Comment') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-6">
            <!-- Account -->
            <div class="card-body pt-4">
                <form method="POST" action="{{ route('dashboard.ticket-comments.store', $ticket->id) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row p-5">
                        <h3>{{ __('Add New Comment') }}</h3>
                        <div class="mb-4 col-md-12">
                            <label for="name" class="form-label">{{ __('Comment') }}</label><span
                                class="text-danger">*</span>
                            <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" required placeholder="{{ __('Enter comment') }}" autofocus>{{ old('comment') }}</textarea>
                            @error('comment')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-3">{{ __('Add Comment') }}</button>
                    </div>
                </form>

            </div>
            <!-- /Account -->
        </div>
    </div>
@endsection

@section('script')
    <script>
        //
    </script>
@endsection
