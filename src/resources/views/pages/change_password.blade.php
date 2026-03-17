@extends('laravel-admin::layout.dashboard')
@section('content')
    <section>
        <div class="mt-2 dashboard-title">
            Change password
        </div>
        <p class="mt-3 paragraph-text">
            Please enter your old password, for security’s sake, and then enter your new password twice so we can verify you
            typed it in correctly.
        </p>
        <div class="mt-3">
            <form action="{{ url( PREFIX_ADMIN_FOR_ROUTES . 'change-password' ) }}" method="POST" class="p-4">
                @csrf
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="inputPassword6" class="col-form-label">Old password:</label>
                    </div>
                    <div class="col-auto px-10 input-group-sm">
                        <input type="password" id="inputPassword6" class="form-control"
                            aria-describedby="passwordHelpInline" name="old_password">
                    </div>
                </div>
                <hr>
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="inputPassword6" class="col-form-label">New password:</label>
                    </div>
                    <div class="col-auto px-10 input-group-sm">
                        <input type="password" name="password" id="inputPassword6" class="form-control"
                            aria-describedby="passwordHelpInline">
                    </div>
                </div>
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="inputPassword6" class="col-form-label"></label>
                    </div>
                    <div class="col-auto px-10 mt-4">
                       {{--  <div class="text-small">Your password can’t be too similar to your other personal information.</div>  --}}
                       <div class="text-small">Your password must contain at least 8 characters.</div>
                       {{--  <div class="text-small">Your password can’t be a commonly used password.</div>  --}}
                       {{--  <div class="text-small">Your password can’t be entirely numeric.</div>  --}}
                    </div>
                </div>
                <hr>
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="inputPassword6" class="col-form-label">New password confirmation:</label>
                    </div>
                    <div class="col-auto px-10 input-group-sm">
                        <input type="password" name="password_confirmation" id="inputPassword6" class="form-control"
                            aria-describedby="passwordHelpInline">
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-end mt-4 bg-grey p-3">
                    <button class="btn btn-cayn">CHANGE MY PASSWORD</button>
                </div>
            </form>
        </div>
    </section>
@endsection
