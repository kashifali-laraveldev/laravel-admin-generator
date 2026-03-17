@extends('layouts.app')

@section('content')
    <section>
        <div class="container auth-content">
            <div class="row justify-content-center">
                <div class="col-md-4 ">
                    <div class="card card-shadow">
                        <div class="text-center mt-4">
                            <div class="page-title">{{ __('Reset Password') }}</div>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            <div class="bg-icon">
                                <i class="fa-solid fa-user-lock fa-2xl"></i>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf

                                <div class="mb-3 mt-4">
                                    <input id="email" type="email"
                                        class="form-control py-10 @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="User Name or Email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="row mb-0 mt-5">
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="button_slide slide_left">
                                            {{ __('Send Password Reset Link') }}
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
    </section>
@endsection
