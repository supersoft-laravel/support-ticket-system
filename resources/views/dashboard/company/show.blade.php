@extends('layouts.master')

@section('title', __('Company Details'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.companies.index') }}">{{ __('Companies') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Details') }}</li>
@endsection
@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row invoice-preview">
            <!-- Invoice -->
            <div class="col-xl-12 col-md-12 col-12 mb-4">
                <div class="card invoice-preview-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                            <div>
                                <div class="mb-2">
                                    <h4 class="invoice-title mb-0">{{ $company->name }}</h4>
                                </div>
                                <div class="mb-2">
                                    <span class="fw-semibold">{{ __('Email') }}: </span><span>{{ $company->email }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="fw-semibold">{{ __('Phone') }}: </span><span>{{ $company->phone }}</span>
                                </div>
                                <div class="mb-2 d-flex align-items-center gap-2">
                                    <span class="fw-semibold">{{ __('Token') }}:</span>

                                    <span id="tokenText" data-token="{{ $company->token }}">
                                        {{ str_repeat('*', 14) }}
                                    </span>

                                    <button type="button" id="toggleToken"
                                        style="border:none; background:transparent; cursor:pointer;">
                                        <i class="ti ti-eye me-0 me-sm-1 ti-xs"></i>
                                    </button>
                                </div>

                                <div class="mb-2">
                                    <span class="fw-semibold">{{ __('Status') }}: </span>
                                    <span
                                        class="badge me-4 bg-label-{{ $company->is_active == 'active' ? 'success' : 'danger' }}">{{ ucfirst($company->is_active) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Invoice -->
        </div>
    </div>
    <!-- / Content -->
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tokenText = document.getElementById('tokenText');
        const toggleBtn = document.getElementById('toggleToken');

        let isVisible = false;

        toggleBtn.addEventListener('click', function () {
            if (isVisible) {
                tokenText.textContent = '**************';
                toggleBtn.innerHTML = '<i class="ti ti-eye me-0 me-sm-1 ti-xs"></i>';
            } else {
                tokenText.textContent = tokenText.dataset.token;
                toggleBtn.innerHTML = '<i class="ti ti-eye-off me-0 me-sm-1 ti-xs"></i>';
            }

            isVisible = !isVisible;
        });
    });
</script>
@endsection
