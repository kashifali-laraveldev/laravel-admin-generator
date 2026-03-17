@extends('laravel-admin::layout.dashboard')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--  Monaco Editor CSS  --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.30.1/min/vs/editor/editor.main.css" />
@endsection

@section('content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-12 d-flex no-block align-items-center">
                    <h4 class="dashboard-title">Edit Database Schema of <b>"{{ $schema_model->model_name }}" Use _image postfix for image field e.g. $field_name_image</b></h4>
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
                    <div class="mb-3">
                        <a href="{{ request()->url() }}" class="btn btn-warning">Open Migration</a>
                        <a href="{{ request()->url() . '?open=model' }}" class="btn btn-primary">Open Model</a>
                        <a href="{{ url('admin/crud-schema/' . base64_encode(json_encode($schema_model)) . '/migrate') }}" data-url="{{ url('admin/crud-schema/' . base64_encode(json_encode($schema_model)) . '/migrate') }}" class="btn btn-success mx-2 @if ($schema_model->is_schema_migrated == 1) remove_pointer_events @endif">Migrate</a>
                    </div>
                    <div class="card p-3 card-shadow">
                        {{--  Model Editor  --}}
                        <h4 class="dashboard-title">
                            <strong>{{ $file_title ?? 'N/A' }} Class</strong>
                        </h4>

                        <div class="row">
                            <div class="col-md-12 mb-3 mt-2">
                                <div id="file_editor" style="height: 500px;">

                                </div>
                                <div class="float-end">
                                    <button type="button" class="button_slide slide_right_navy" id="file_save_button" name="action"
                                        value="save">
                                        {{ __('Save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
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

@section('script')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

{{-- Monaco Editor JS  --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.30.1/min/vs/loader.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.30.1/min/vs' }});

            // Pass the PHP content to JavaScript
            var initialContent = @json($file_content);
            require(["vs/editor/editor.main"], function () {
                window.editor = monaco.editor.create(document.getElementById("file_editor"), {
                    value: initialContent,
                    language: 'php',
                    theme: 'vs-dark',
                });

                // Listener for save button
                document.getElementById("file_save_button").addEventListener('click', function() {
                    var editedContent = window.editor.getValue();
                    var saveUrl = '{{ url("admin/crud-schema/" . $schema_model_id . "/editor/save") }}';
                    console.log(saveUrl);
                    {{--  return true;  --}}
                    fetch(saveUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({ content: editedContent, _token: '{{ csrf_token() }}', file_path: @json($file_path), editor_name: '{{ $file_title }}' }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.result)
                        {
                            toastr.success(data.message)
                        }else{
                            toastr.error(data.message)
                        }

                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        toastr.error(error);
                    });
                });
            });



    });
</script>
@endsection
