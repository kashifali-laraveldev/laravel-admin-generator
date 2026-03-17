@extends('laravel-admin::layout.dashboard')

@section('css')
    <!-- Include required CSS files -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap.min.css">
@endsection

@section('content')
    <div class="ms-auto text-end pb-5">
        <a href="{{ route('auth_groups.create') }}" class="button_slide slide_right_navy">+ Add New</a>
    </div>
    <div class="card p-4">
        <div class="table-responsive">
            <table id="userTable" class="table table-hover">
                <thead>
                    <tr>
                        <th class="p-3 border-radius-top-left">Group Name</th>
                        <th class="p-3">permission Name</th>
                        <th class="p-3 border-radius-top-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <!-- DataTables will populate the data here -->
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

    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('auth_groups.index') }}",
                "columns": [{
                        "data": "group_name"
                    },
                    {
                        "data": "permission_name"
                    },
                    {
                        "data": "action"
                    }
                ]
            });
        });
    </script>
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
                        swal("Your imaginary file is safe!");
                    }
                });
        });
    </script>
@endsection
