@extends('laravel-admin::layout.dashboard')
@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
@endsection
@section('content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-12 d-flex no-block align-items-center">
                    <h4 class="dashboard-title">Users</h4>
                    <div class="ms-auto text-end">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">
                                        <button class="button_slide_success slide_right">View List</button></a></li>
                                {{-- <li class="breadcrumb-item active" aria-current="page">
                                    create
                                </li> --}}
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="">
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card p-3 card-shadow">
                        <form method="POST" action="{{ route('users.store') }}">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="user_id"
                                    @if (isset($user)) value="{{ $user->id }}" @endif />
                                <div class="col-md-6 mb-3 mt-2">
                                    <input id="first_name" type="text"
                                        class="form-control py-10 @error('first_name') is-invalid @enderror"
                                        name="first_name"
                                        @if (isset($user)) value="{{ $user->first_name }}" @else value="{{ old('first_name') }}" @endif
                                        required autocomplete="first_name" autofocus placeholder="First Name">

                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6 mt-2">
                                    <input id="last_name" type="text"
                                        class="form-control py-10 @error('last_name') is-invalid @enderror" name="last_name"
                                        @if (isset($user)) value="{{ $user->last_name }}" @else value="{{ old('last_name') }}" @endif
                                        required autocomplete="last_name" autofocus placeholder="Last Name">

                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>


                                <div class="mb-3 col-md-6">
                                    <input id="email" type="email"
                                        class="form-control py-10 @error('email') is-invalid @enderror" name="email"
                                        @if (isset($user)) value="{{ $user->email }}" @else value="{{ old('email') }}" @endif
                                        required autocomplete="email" placeholder="Email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                <div class="mb-3 col-md-6">
                                    <input id="username" type="text"
                                        class="form-control py-10 @error('username') is-invalid @enderror" name="username"
                                        @if (isset($user)) value="{{ $user->username }}" @else value="{{ old('username') }}" @endif
                                        required autocomplete="username" autofocus placeholder="User Name">

                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                <div class="mb-3 col-md-6">
                                    <input id="password" type="password"
                                        class="form-control py-10 @error('password') is-invalid @enderror" name="password"
                                        autocomplete="new-password" placeholder="Password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                @if (!isset($user))
                                    <div class="mb-3 col-md-6">
                                        <input id="password-confirm" type="password" class="form-control py-10"
                                            name="password_confirmation" required autocomplete="new-password"
                                            placeholder="Confirm Password">

                                    </div>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3 mt-2">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_active"
                                            id="is_laravel_admin_used"
                                            class="form-check-input"
                                             value="1" @if(isset($user) && $user->is_active == 1) checked @endif>
                                        <label class="form-check-label"
                                            for="is_active">{{ 'Status' }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mb-0 mt-4">
                                <div class="">
                                    <button type="submit" class="button_slide slide_right_navy" name="action"
                                        value="save">
                                        {{ __('Submit') }}
                                    </button>
                                    <button type="submit" class="button_slide_success slide_right" name="action"
                                        value="save_and_add">
                                        Save and Add Another
                                    </button>
                                    <button type="submit" class="button_slide_teel slide_info" name="action"
                                        value="save_and_edit">
                                        Save and Add Continue Editing
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>


                </div>

            </div>
            <!-- editor -->

            <!-- ============================================================== -->
            <!-- End PAge Content -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Right sidebar -->
            <!-- ============================================================== -->
            <!-- .right-sidebar -->
            <!-- ============================================================== -->
            <!-- End Right sidebar -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->

        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
@endsection
