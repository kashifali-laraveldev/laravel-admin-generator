@extends('laravel-admin::layout.dashboard')

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
                    <div class="card p-3 card-shadow">
                        <form method="POST" action="{{ url('admin/crud-schema/' . $schema_model_id . '/store') }}">
                            @csrf
                            <div class="table_columns_block">
                                <div class="row" id="table_columns_block_row-1">
                                    <div class="col-md-2 mb-3 mt-2">
                                        <input id="column_name" type="text" class="form-control py-10" name="column_name[0]"
                                            required autocomplete="column_name" autofocus placeholder="Column Name">
                                    </div>
                                    <div class="col-md-2 mb-3 mt-2">
                                        <select name="data_type[0]" id="data_type"
                                            class="form-select py-10" required>
                                            <option value="" selected disabled>Select Data Type</option>
                                            @if (isset($schema_data_types))
                                                @foreach ($schema_data_types as $data_type)
                                                    <option value="{{ $data_type }}"> {{ $data_type }}
                                                @endforeach
                                            @endif
                                        </select>

                                    </div>
                                    <div class="col-md-2 mb-3 mt-2">
                                        <select name="default_null[0]" id="default_null"
                                            class="form-select py-10" required>
                                            <option value="" selected disabled>Default Null </option>
                                            <option value="1"> Yes </option>
                                            <option value="0"> No </option>
                                        </select>

                                    </div>
                                    <div class="col-md-2 mb-3 mt-2">
                                        <input id="default_value" type="text" class="form-control py-10" name="default_value[0]"
                                         autocomplete="default_value" autofocus placeholder="Default Value">
                                    </div>
                                    <div class="col-md-2 mb-3 mt-2 remove_button_schema d-none">
                                        <div class="float-start">
                                            <button type="button" class="btn btn-danger">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3 mt-2">

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 mb-3 mt-2"></div>
                                <div class="col-md-2 mb-3 mt-2">
                                    <div class="float-start">
                                        <button type="button" class="btn btn-primary add_more_schema">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3 mt-2">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_laravel_admin_used"
                                            id="is_laravel_admin_used"
                                            class="form-check-input"
                                             value="1">
                                        <label class="form-check-label"
                                            for="is_laravel_admin_used">{{ 'Use Laravel Admin Trait' }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mb-0 mt-4">
                                <div class="">
                                    <button type="submit" class="button_slide slide_right_navy" name="action"
                                        value="save">
                                        {{ __('Save') }}
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

@section('script')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script>
    function getMakeAndSerialLength()
    {
        return $(".table_columns_block .row").length;
    }
    function schemaAddMore()
    {
        var pointer = getMakeAndSerialLength();
        var content = $('#table_columns_block_row-1').html();
        $(".table_columns_block").append('<div class="row" id="table_columns_block_row-'+(pointer+1)+'">'+content+'</div>');
        $('#table_columns_block_row-'+(pointer+1)).find('.remove_button_schema').removeClass('d-none');
        {{--  Form Fields name attribute update in Block  --}}
        $('#table_columns_block_row-'+(pointer+1)).find('[name="column_name[0]"]').attr('name','column_name['+(pointer)+']');
        $('#table_columns_block_row-'+(pointer+1)).find('[name="data_type[0]"]').attr('name','data_type['+(pointer)+']');
        $('#table_columns_block_row-'+(pointer+1)).find('[name="default_null[0]"]').attr('name','default_null['+(pointer)+']');
        $('#table_columns_block_row-'+(pointer+1)).find('[name="default_value[0]"]').attr('name','default_value['+(pointer)+']');
        $('#table_columns_block_row-'+(pointer+1)).find('[name="enum_value[0]"]').attr('name','enum_value['+(pointer)+']');
    }
    function refreshCounterSchema()
    {
        var count = 1;
        $('.make_and_serial .row').each(function()
        {
            $(this).find('.item-make-serial-count').text(count);
            count++;
        });
        if(make_and_serial == 2)
            $('.equipment_plus_btn').fadeIn();
            make_and_serial--;
    }
    {{--  Events For Add More  --}}
    $(document).on('click', '.remove_button_schema', function(){
        $(this).closest('.row').remove();
        refreshCounterSchema();
    });
    $(document).on("click",".add_more_schema",function (e)
    {
        e.preventDefault();
        schemaAddMore();
    });
</script>
@endsection
