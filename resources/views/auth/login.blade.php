@extends('layouts.authentication.master')
@section('title', 'Login')

@section('css')
@endsection

@section('content')

    <div class="card">
        <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-6">
                <a href="" class="app-brand-link">
                    <span class="app-brand-logo">
                        <span class="text-primary">
                            <img height="60px" src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}"
                                alt="{{ \App\Helpers\Helper::getCompanyName() }}">
                        </span>
                    </span>
                    {{-- <span class="app-brand-text demo text-heading fw-bold">{{ \App\Helpers\Helper::getCompanyName() }}</span> --}}
                </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-1">{{ __('Welcome to') }} {{ \App\Helpers\Helper::getCompanyName() }}!</h4>
            <p class="mb-6">{{ __('Please sign-in to your account and start the adventure') }}</p>

            <form id="formLogin" class="mb-6" action="{{ route('login.attempt') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="email_username" class="form-label">{{ __('Email/Username') }}</label><span
                        class="text-danger">*</span>
                    <input type="text" class="form-control @error('email_username') is-invalid @enderror"
                        id="email_username" name="email_username" placeholder="{{ __('Enter your email or username') }}"
                        autofocus required />
                    @error('email_username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-6 form-password-toggle">
                    <label class="form-label" for="password">{{ __('Password') }}</label><span class="text-danger">*</span>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                            name="password"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="password" required />
                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="my-8">
                    <div class="d-flex justify-content-between">
                        <div class="form-check mb-0 ms-2">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember-me" />
                            <label class="form-check-label" for="remember-me"> {{ __('Remember Me') }} </label>
                        </div>
                        {{-- @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">
                                <p class="mb-0">{{ __('Forgot Password?') }}</p>
                            </a>
                        @endif --}}
                    </div>
                </div>
                <div class="my-8">
                    @if (config('captcha.version') === 'v3')
                        {!! \App\Helpers\Helper::renderRecaptcha('formLogin', 'register') !!}
                    @elseif(config('captcha.version') === 'v2')
                        <div class="form-field-block">
                            {!! app('captcha')->display() !!}
                            @if ($errors->has('g-recaptcha-response'))
                                <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                            @endif
                        </div>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary d-grid w-100">{{ __('Sign in') }}</button>
            </form>

            {{-- <p class="text-center">
                <span>New on our platform?</span>
                <a href="auth-register-basic.html">
                    <span>Create an account</span>
                </a>
            </p>

            <div class="divider my-6">
                <div class="divider-text">or</div>
            </div>

            <div class="d-flex justify-content-center">
                <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-facebook me-1_5">
                    <i class="icon-base ti tabler-brand-facebook-filled icon-20px"></i>
                </a>

                <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-twitter me-1_5">
                    <i class="icon-base ti tabler-brand-twitter-filled icon-20px"></i>
                </a>

                <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-github me-1_5">
                    <i class="icon-base ti tabler-brand-github-filled icon-20px"></i>
                </a>

                <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-google-plus">
                    <i class="icon-base ti tabler-brand-google-filled icon-20px"></i>
                </a>
            </div> --}}
        </div>
    </div>
@endsection

@section('script')
    {!! NoCaptcha::renderJs() !!}
@endsection
