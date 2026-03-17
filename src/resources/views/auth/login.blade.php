@extends('laravel-admin::layouts.app')

@section('content')
    <section class="auth-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="card card-shadow">
                        <div class="text-center pt-4">
                            <h1 class="page-title">{{ __('Login') }}</h1>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            <div class="bg-icon">
                                <i class="fa-solid fa-fingerprint fa-2xl"></i>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="row mb-3 mt-4">
                                    <div class="mb-3 mt-2">
                                        <input id="email_or_username" type="text"
                                            class="form-control py-10 @error('email_or_username') is-invalid @enderror"
                                            name="email_or_username" value="{{ old('email_or_username') }}" required
                                            autocomplete="email_or_username" autofocus placeholder="User Name">
                                        @if ($message = Session::get('error'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @endif
                                        @error('email_or_username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3 mt-2">
                                        <input id="password" type="password"
                                            class="form-control py-10 @error('password') is-invalid @enderror"
                                            name="password" required autocomplete="current-password" placeholder="Password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">

                                    <div class="d-flex justify-content-between">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                                {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                        <div>
                                            @if (Route::has('register'))
                                                <a class="nav-link"
                                                    href="{{ route('register') }}">{{ __('Create new account') }}</a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center">

                                        <div class="mt-3">
                                            @if (Route::has('password.request'))
                                                <a class="btn-link" href="{{ route('password.request') }}">
                                                    {{ __('Forgot Your Password?') }}
                                                </a>
                                            @endif
                                        </div>

                                    </div>
                                </div>

                                <div class="row mb-0 mt-5">
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="button_slide slide_right">
                                            {{ __('Login') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
