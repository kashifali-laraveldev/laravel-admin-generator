@extends('laravel-admin::layout.dashboard')

@section('css')
    <!-- Include required CSS files -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap.min.css">
@endsection

@section('content')
    <div class="d-flex justify-content-between">
        <div class="dashboard-title">{{ $crud->model_plural_text }}</div>
        <div class="ms-auto text-end pb-5">
            <a href="{{ url('admin/crud/' . $crud_id . '/create') }}" class="button_slide slide_right_navy">+ Add New</a>
        </div>
    </div>

    <div class="card p-4">

        <div class="table-responsive">
            <table id="userTable" class="table table-hover">
                <thead>
                    <tr>
                        @foreach ($model_fillables as $title)
                            <th class="p-3">{{ ucfirst($title) }}</th>
                        @endforeach
                        <th class="p-3 border-radius-top-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                <tbody class="text-center">
                    <!-- DataTables will populate the data here -->
                </tbody>
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
    {{--  <script>
        var data = JSON.parse('{{ ($model_fillables_json) }}')
        console.log("Parse data " + data)
        /*
        $(document).ready(function() {
            $('#userTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ url('laravel_admin/' . $crud_id . '/index') }}",
                "columns":
            });
        });
        */
    </script>  --}}
    <script>
        $(document).ready(function() {

            $('#userTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ url('admin/crud/' . $crud_id) }}",
                "columns": [
                    @foreach ($model_fillables as $column)
                        {
                            "data": "{{ $column }}"
                        },
                    @endforeach {
                        "data": "action"
                    }
                ]
            });
        });
    </script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>
        $(document).on('submit', '.delete', function(e) {
            var record = this;
            e.preventDefault();
            var url = $(this).attr('action');
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: "DELETE",
                            url: url,
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(resp) {
                                $(record).parents("tr").remove();
                                swal("Success! Data has been deleted!", {
                                    icon: "success",
                                });
                                //   window.location.reload();
                            }
                        });
                    } else {
                        swal("Your data is safe!");
                    }
                });
        });
    </script>
@endsection
