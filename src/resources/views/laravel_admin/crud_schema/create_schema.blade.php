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
                    <h4 class="dashboard-title">Create Model</h4>
                    <div class="ms-auto text-end">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('admin/crud-schema') }}">
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
                        <form method="POST" action="{{ url('admin/crud-schema/create-model') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-3 mt-2">
                                    <input id="model_name" type="text" class="form-control py-10" name="model_name"
                                        required autocomplete="model_name" autofocus placeholder="Model Name">
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
