@extends('laravel-admin::layout.dashboard')

@section('content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-12 d-flex no-block align-items-center">
                    <h4 class="dashboard-title">{{ $crud->model_plural_text }}</h4>
                    <div class="ms-auto text-end">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('admin/crud/' . $crud_id) }}">
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
                        <form method="POST" action="{{ url('admin/crud/' . $crud_id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                @foreach ($table_structure as $table_column)
                                    @php
                                        $columnName = $table_column->Field;
                                    @endphp
                                    @if ($table_column->data_type == DB_DATATYPE_INTEGER && $table_column->Field == DB_TABLE_PRIMARY_COLUMN)
                                        <input type="hidden" name="model_object_id"
                                            @if (isset($model_object)) value="{{ $model_object[$table_column->Field] }}" @endif />
                                    @elseif($table_column->data_type == DB_DATATYPE_VARCHAR)
                                        <div class="col-md-6 mb-3 mt-2">
                                            <label for="" class="text-grey">{{ $table_column->field_label }}</label>
                                            <input id="{{ $table_column->Field }}" type="text"
                                                class="form-control py-10 @error("{{ $table_column->Field }}") is-invalid @enderror"
                                                name="{{ $table_column->Field }}"
                                                @if (isset($model_object)) value="{{ $model_object->$columnName }}" @else value="{{ old(" $table_column->Field ") }}" @endif
                                                autocomplete="{{ $table_column->Field }}" autofocus
                                                placeholder="{{ $table_column->field_label }}">

                                            @error("{{ $table_column->Field }}")
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    @elseif($table_column->data_type == DB_DATATYPE_INTEGER)
                                        <div class="col-md-6 mb-3 mt-2">
                                            <label for=""
                                                class="text-grey">{{ $table_column->field_label }}</label>
                                            <input id="{{ $table_column->Field }}" type="text"
                                                class="form-control py-10 input_integer_number @error("{{ $table_column->Field }}") is-invalid @enderror la_whole_number"
                                                name="{{ $table_column->Field }}"
                                                @if (isset($model_object)) value="{{ $model_object->$columnName }}" @else value="{{ old(" $table_column->Field ") }}" @endif
                                                autocomplete="{{ $table_column->Field }}" autofocus
                                                placeholder="{{ $table_column->field_label }}, i.e 245">

                                            @error("{{ $table_column->Field }}")
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    @elseif($table_column->data_type == DB_DATATYPE_FLOAT)
                                        <div class="col-md-6 mb-3 mt-2">
                                            <label for=""
                                                class="text-grey">{{ $table_column->field_label }}</label>
                                            <input id="{{ $table_column->Field }}" type="text"
                                                class="form-control py-10 input_float_number @error("{{ $table_column->Field }}") is-invalid @enderror la_float_number"
                                                name="{{ $table_column->Field }}"
                                                @if (isset($model_object)) value="{{ $model_object->$columnName }}" @else value="{{ old(" $table_column->Field ") }}" @endif
                                                autocomplete="{{ $table_column->Field }}" autofocus
                                                placeholder="{{ $table_column->field_label }}, i.e 45.3">

                                            @error("{{ $table_column->Field }}")
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    @elseif($table_column->data_type == DB_DATATYPE_ENUM)
                                        <div class="col-md-6 mb-3 mt-2">
                                            <label for=""
                                                class="text-grey">{{ $table_column->field_label }}</label>
                                            <select name="{{ $table_column->Field }}" id="{{ $table_column->Field }}"
                                                class="form-select py-10 @error("{{ $table_column->Field }}") is-invalid @enderror">
                                                <option value="" selected disabled>Select Option</option>
                                                @if (isset($table_column->enum_options))
                                                    @foreach ($table_column->enum_options as $enum_option)
                                                        <option value="{{ $enum_option }}"
                                                            @if (isset($model_object) && isset($model_object->$columnName) && $model_object->$columnName == $enum_option) selected @endif>
                                                            {{ $enum_option }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error("{{ $table_column->Field }}")
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    @elseif($table_column->data_type == DB_DATATYPE_TEXT || $table_column->data_type == DB_DATATYPE_LONGTEXT)
                                        <div class="col-md-12 mb-3 mt-2">
                                            <label for=""
                                                class="text-grey">{{ $table_column->field_label }}</label>
                                            <textarea placeholder="{{ $table_column->field_label }}" name="{{ $table_column->Field }}"
                                                id="{{ $table_column->Field }}"
                                                class="form-control py-10 @error("{{ $table_column->Field }}") is-invalid @enderror" cols="30" rows="10">
@if (isset($model_object) && isset($model_object->$columnName))
{{ $model_object->$columnName }}
@endif
</textarea>
                                            @error("{{ $table_column->Field }}")
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    @elseif($table_column->data_type == DB_DATATYPE_TIMESTAMP)
                                        <div class="col-md-6 mb-3 mt-2">
                                            <label for=""
                                                class="text-grey">{{ $table_column->field_label }}</label>
                                            <input step="any"
                                                class="form-control py-10 @error("{{ $table_column->Field }}") is-invalid @enderror"
                                                type="datetime-local"
                                                @if (isset($model_object) && isset($model_object->$columnName)) value="{{ $model_object->$columnName }}" @else value="{{ date('Y-m-d H:i:s') }}" @endif
                                                name="{{ $table_column->Field }}" id="{{ $table_column->Field }}"
                                                placeholder="{{ $table_column->Field }}">
                                            @error("{{ $table_column->Field }}")
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    @elseif($table_column->data_type == DB_DATATYPE_IMAGE)
                                        <div class="col-md-12 mb-3 mt-2">
                                            <label for=""
                                                class="text-grey">{{ $table_column->field_label }}</label>
                                            <input
                                                class="form-control py-10 @error("{{ $table_column->Field }}") is-invalid @enderror"
                                                type="file" name="{{ $table_column->Field }}"
                                                id="{{ $table_column->Field }}" accept="image/*">
                                            @error("{{ $table_column->Field }}")
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        @if (isset($model_object) && isset($model_object->$columnName))
                                            <div class="col-md-12 mb-3 mt-2">
                                                <img src="{{ asset($model_object->$columnName) }}" width="200"
                                                    alt="">
                                            </div>
                                        @endif
                                    @elseif($table_column->data_type == DB_DATATYPE_DATETIME)
                                        <div class="col-md-6 mb-3 mt-2">
                                            <label for=""
                                                class="text-grey">{{ $table_column->field_label }}</label>
                                            <input step="any"
                                                class="form-control py-10 @error("{{ $table_column->Field }}") is-invalid @enderror"
                                                type="datetime-local"@if (isset($model_object) && isset($model_object->$columnName)) value="{{ $model_object->$columnName }}" @else value="{{ date('Y-m-d H:i:s') }}" @endif
                                                name="{{ $table_column->Field }}" id="{{ $table_column->Field }}"
                                                placeholder="{{ $table_column->Field }}">
                                            @error("{{ $table_column->Field }}")
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    @elseif($table_column->data_type == DB_DATATYPE_DATE)
                                        <div class="col-md-6 mb-3 mt-2">
                                            <label for=""
                                                class="text-grey">{{ $table_column->field_label }}</label>
                                            <input step="any"
                                                class="form-control py-10 @error("{{ $table_column->Field }}") is-invalid @enderror"
                                                type="date"
                                                @if (isset($model_object) && isset($model_object->$columnName)) value="{{ $model_object->$columnName }}" @else value="{{ date('Y-m-d') }}" @endif
                                                name="{{ $table_column->Field }}" id="{{ $table_column->Field }}"
                                                placeholder="{{ $table_column->Field }}">
                                            @error("{{ $table_column->Field }}")
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    @elseif($table_column->data_type == DB_DATATYPE_BOOLEAN)
                                        <div class="col-md-6 mb-3 mt-2">

                                            <div class="d-flex">
                                                <label for=""
                                                    class="text-grey mx-2">{{ $table_column->field_label }}</label>
                                                <div class="form-check">
                                                    <input type="radio" name="{{ $table_column->Field }}"
                                                        id="{{ $table_column->Field }}-yes"
                                                        class="form-check-input @error("{{ $table_column->Field }}") is-invalid @enderror"
                                                        @if (isset($model_object) && isset($model_object->$columnName) && $model_object->$columnName == 1) checked @endif value="1">
                                                    <label class="form-check-label"
                                                        for="{{ $table_column->Field }}">Yes</label>
                                                </div>
                                                <div class="form-check mx-3">
                                                    <input type="radio" name="{{ $table_column->Field }}"
                                                        id="{{ $table_column->Field }}-no"
                                                        class="form-check-input @error("{{ $table_column->Field }}") is-invalid @enderror"
                                                        @if (isset($model_object) && isset($model_object->$columnName) && $model_object->$columnName == 0) checked @endif value="0">
                                                    <label class="form-check-label" for="{{ $table_column->Field }}"> No
                                                    </label>
                                                </div>
                                            </div>
                                            @error("{{ $table_column->Field }}")
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    @endif
                                @endforeach
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
        </div>
    </div>
@endsection


@section('footer')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
@endsection
