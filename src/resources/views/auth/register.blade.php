@extends('laravel-admin::layouts.app')

@section('content')
    <section class="auth-content mt-4 mb-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card card-shadow">
                                <div class="text-center pt-4">
                                    <div class="page-title">{{ __('Register') }}</div>
                                </div>
                                <div class="d-flex justify-content-center mt-4">
                                    <div class="bg-icon">
                                        <i class="fa-solid fa-user-plus fa-2xl"></i>
                                    </div>
                                </div>
                                <div class="card-body p-4">
                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf

                                        <div class="mb-3 mt-2">
                                            <input id="first_name" type="text"
                                                class="form-control py-10 @error('first_name') is-invalid @enderror"
                                                name="first_name" value="{{ old('first_name') }}" required
                                                autocomplete="first_name" autofocus placeholder="First Name">

                                            @error('first_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <input id="last_name" type="text"
                                                class="form-control py-10 @error('last_name') is-invalid @enderror"
                                                name="last_name" value="{{ old('last_name') }}" required
                                                autocomplete="last_name" autofocus placeholder="Last Name">

                                            @error('last_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                        </div>

                                        <div class="mb-3">
                                            <input id="email" type="email"
                                                class="form-control py-10 @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email') }}" required autocomplete="email"
                                                placeholder="Email">

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>


                                        <div class="mb-3">
                                            <input id="username" type="text"
                                                class="form-control py-10 @error('username') is-invalid @enderror"
                                                name="username" value="{{ old('username') }}" required
                                                autocomplete="username" autofocus placeholder="User Name">

                                            @error('username')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>


                                        <div class="mb-3">
                                            <input id="password" type="password"
                                                class="form-control py-10 @error('password') is-invalid @enderror"
                                                name="password" required autocomplete="new-password" placeholder="Password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <input id="password-confirm" type="password" class="form-control py-10"
                                                name="password_confirmation" required autocomplete="new-password"
                                                placeholder="Confirm Password">

                                        </div>

                                        <div class="row mb-0 mt-4">
                                            <div class="col-md-6 offset-md-4">
                                                <button type="submit" class="button_slide slide_left">
                                                    {{ __('Register') }}
                                                </button>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center mt-4">
                                            @if (Route::has('login'))
                                                <a class="nav-link" href="{{ route('login') }}">{{ __('Go Back To Login') }}</a>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
