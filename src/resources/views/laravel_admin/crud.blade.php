@extends('laravel-admin::layout.dashboard')

@section('css')
    <!-- Include required CSS files -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap.min.css">
@endsection

@section('content')
    <div class="page-breadcrumb mb-3">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="dashboard-title">CRUDs</h4>

            </div>
        </div>
    </div>
    <div class="card p-4">
        <div class="table-responsive">
            <table id="userTable" class="table table-hover">
                <thead>
                    <tr>
                        <th class="p-3 border-radius-top-left">CRUD</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse($crud_list as $crudKey => $crud)
                        <tr style="cursor: pointer;" class="crud_list" data-model="{{ base64_encode($crud) }}">
                            <td>
                                {{ $crudKey }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td>No data available in table</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <!-- Include required JS files -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(document).on("click", ".crud_list", function() {
            var model = $(this).attr("data-model");
            //alert("{{ url('laravel_admin') }}/" + model);
            window.location.href = "{{ url('admin') }}/crud/" + model;
        });
    </script>
@endsection
