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
                <h4 class="dashboard-title">Database Schema List</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/crud') }}">
                                    <a href="{{ url('admin/crud-schema/create') }}" class="button_slide slide_right_navy">+
                                        Add
                                        New</a>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="card p-4">
        <div class="table-responsive">
            <table id="userTable" class="table table-hover">
                <thead>
                    <tr>
                        <th class="p-3 border-radius-top-left">Model</th>
                        <th class="p-3">Table</th>
                        <th class="p-3">LaravelAdmin Trait</th>
                        <th class="p-3">Migration Status</th>
                        <th class="p-3">Action</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse($schema_list as $schema)
                        <tr class="crud_list" data-model="{{ url('admin/crud-schema/' . base64_encode(json_encode($schema)) . '/edit') }}">
                            <td>
                                {{ $schema['model_name'] }}
                            </td>
                            <td>
                                {{ $schema['table_name'] }}
                            </td>
                            <td>
                                @if ($schema['is_trait_used'] == 1)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-info">No</span>
                                @endif
                            </td>
                            <td>
                                @if ($schema['is_schema_migrated'] == 1)
                                    <span class="badge bg-success">Migrated</span>
                                @else
                                    <span class="badge bg-info">Not migrated</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($schema['is_schema_created']) && $schema['is_schema_created'] == '1')
                                    <a href="{{ url('admin/crud-schema/' . base64_encode(json_encode($schema)) . '/editor') }}"
                                        class="btn btn-primary @if ($schema['is_schema_migrated'] == 1) remove_pointer_events @endif">Open Editor</a>
                                    <a href="{{ url('admin/crud-schema/' . base64_encode(json_encode($schema)) . '/migrate') }}" data-url="{{ url('admin/crud-schema/' . base64_encode(json_encode($schema)) . '/migrate') }}" class="btn btn-success mx-2 @if ($schema['is_schema_migrated'] == 1) remove_pointer_events @endif">Migrate</a>
                                @else
                                    <a href="{{ url('admin/crud-schema/' . base64_encode(json_encode($schema)) . '/edit') }}"
                                    class="btn btn-warning @if ($schema['is_schema_migrated'] == 1) d-none @endif">Create Schema</a>
                                @endif


                                <button href="javascript:void(0);" data-url="{{ url('admin/crud-schema/' . base64_encode(json_encode($schema)) . '/delete') }}" class="btn btn-danger mx-2 delete">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100">No data available in table</td>
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
            return true;
            var modelUrl = $(this).attr("data-model");
            //alert("{{ url('laravel_admin') }}/" + model);
            window.location.href = modelUrl;
        });
    </script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>
        $(document).on('click', '.delete', function(e) {
            var record = this;
            e.preventDefault();
            var url = $(this).attr('data-url');
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
                            type: "POST",
                            url: url,
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(resp) {
                                $(record).parents("tr").remove();
                                swal("Poof! Data has been deleted!", {
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
