@extends('layouts.master')

@section('title', __('Edit Company'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.companies.index') }}">{{ __('Companies') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-6">
            <!-- Account -->
            <div class="card-body pt-4">
                <form method="POST" action="{{ route('dashboard.companies.update', $company->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row p-5">
                        <h3>{{ __('Edit Company') }}</h3>
                        <div class="mb-4 col-md-12">
                            <label for="name" class="form-label">{{ __('Company Name') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('name') is-invalid @enderror" type="text"
                                id="name" name="name" required placeholder="{{ __('Enter company name') }}"
                                autofocus value="{{ old('name', $company->name) }}" />
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-12">
                            <label for="email" class="form-label">{{ __('Email') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('email') is-invalid @enderror" type="email"
                                id="email" name="email" required placeholder="{{ __('Enter email') }}"
                                value="{{ old('email', $company->email) }}" />
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-12">
                            <label for="phone" class="form-label">{{ __('Phone') }}</label>
                            <input class="form-control @error('phone') is-invalid @enderror" type="text"
                                id="phone" name="phone" placeholder="{{ __('Enter phone') }}"
                                autofocus value="{{ old('phone', $company->phone) }}" />
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-3">{{ __('Update Company') }}</button>
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
