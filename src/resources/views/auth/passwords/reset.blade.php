@extends('layouts.app')

@section('content')
<div class="container auth-content">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card card-shadow">
               <div class="text-center pt-4">
                <div class="page-title">{{ __('Reset Password') }}</div>
               </div>
               <div class="d-flex justify-content-center mt-4">
                <div class="bg-icon">
                    <i class="fa-solid fa-user-lock fa-2xl"></i>
                </div>
            </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3 mt-4">
                                <input id="email" placeholder="Enter Email" type="email" class="form-control py-10 @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="mb-3">
                                <input id="password" placeholder="Password" type="password" class="form-control py-10 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="mb-3">
                                <input id="password-confirm" placeholder="Confirm Password" type="password" class="form-control py-10" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="row mb-0 mt-4">
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="button_slide slide_right">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
