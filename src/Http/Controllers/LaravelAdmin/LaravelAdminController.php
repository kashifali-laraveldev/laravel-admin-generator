<?php

namespace Bitsoftsol\LaravelAdministration\Http\Controllers\LaravelAdmin;

use App\Http\Controllers\Controller;
use Bitsoftsol\LaravelAdministration\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class LaravelAdminController extends Controller
{
    /*
        * Constructor is here, we are storing the reuseable values here.
    */
    private $default_view, $base_folder;
    public function __construct()
    {
        $this->default_view = 'laravel-admin::laravel_admin.crud.';
        $this->base_folder = 'laravel-admin::laravel_admin.';
    }
    /**
     * Display a listing of the resource.
     */

    public function index($crud_id, Request $request)
    {
        try{
            $crud = getClassModelTitle($crud_id); // Get CRUD model cass, singular title and plural title
            $model_object = new $crud->model_class(); // create object of model cass
            $model_fillables = $model_object->getFillable(); // get model fillables to show columns in table
            $imageColNames = $model_object->LAT_getImageColumnNames($model_object); // Get All column names that contains name like image_*, These columns are for images

            if ($request->ajax()) {
                $raw_columns[] = "action"; // Raw columns Like "action => Edit, Delete HTML", "images HTML"
                $data = $crud->model_class::get(); // Fetch model data from your database
                foreach($data as $key => $value)
                {
                    // Get each data one by one and update all image_* columns with full url
                    $data[$key] = $model_object->LAT_getImageAttribue($value);
                }

                $dataTables = DataTables::of($data)
                    ->addColumn('action', function ($row) use($crud_id){
                        $editRoute = url("admin/crud/" . $crud_id . '/' . $row->id .'/edit');
                        $deleteRoute = url("admin/crud/" . $crud_id . '/' . $row->id);
                        $csrfField = csrf_field();
                        $methodField = method_field('DELETE');

                        return '<a href="' . $editRoute . '" class="btn btn-warning btn-sm">Edit</a>' .
                            '<form class="delete" action="' . $deleteRoute . '" method="POST" style="display: inline; padding-left:10px">' .
                            $csrfField .
                            $methodField .
                            '<button type="submit" class="btn btn-danger btn-sm">Delete</button>' .
                            '</form>';
                    });
                // Image Column Customization in DataTables
                if(isset($imageColNames) && count($imageColNames) > 0)
                {
                    // If Model table contains image columns then update Image fields
                    // 1: Display Image in Table , 2: Click on image then Open in New Tab
                    foreach($imageColNames as $imageCol)
                    {
                        $raw_columns[] = $imageCol;
                        $dataTables->addColumn($imageCol, function ($data) use($imageCol) {
                            $imageUrl = url($data->$imageCol);
                            return '<a href="' . $imageUrl . '" target="_blank"><img src="' . $imageUrl . '" border="0" width="60" class="img-rounded" align="center" /></a>';
                        });
                    }
                }

                return $dataTables->rawColumns($raw_columns)
                    ->make(true);
            }

            return view($this->default_view . 'index', get_defined_vars());

        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     * Get CRUD model cass, singular title and plural title
     * create object of model cass
     * fillable table columns, fillables defined in model
     */
    public function create($crud_id)
    {
        $crud = null;
        $table_structure = $this->getTableStructure($crud_id, $crud);
        return view($this->default_view . 'create', get_defined_vars());
    }

    /**
     * Crud the form for displaying the all models for laravel admin crud.
     */
    public function crud()
    {
        $crud_list = getLaravelAdminCrudList();

        return view($this->base_folder . 'crud', get_defined_vars());
    }


    /**
     * Store a newly created resource in storage.
     * Get CRUD model cass, singular title and plural title
     * Create new object of model class
     */
    public function store($crud_id, Request $request)
    {
        try{
            DB::beginTransaction();
            $inputs = $request->all();
            $crud = getClassModelTitle($crud_id);
            if(isset($inputs['model_object_id']))
            {
                $model_object = $crud->model_class::whereId($inputs['model_object_id'])->firstOrFail();
            }else{
                $model_object = new $crud->model_class();
            }
            $validation = $model_object->LAT_validate($inputs, $model_object);
            if(isset($validation->result))
            {
                if($validation->result == true)
                {
                    $table_structure = $model_object->LAT_TableStructure();
                    $all_columns = collect($table_structure)
                        ->where('Field', '!=', DB_TABLE_PRIMARY_COLUMN)->toArray();

                    foreach($all_columns as $column)
                    {

                        $columnName = $column->Field;
                        if(checkImageColumnExists($column->Field, DB_DATATYPE_IMAGE))
                        {
                            // For Image Fields

                            if(isset($column->Field) && $request->hasFile($column->Field))
                            {

                                if(isset($inputs['model_object_id']) && isset($model_object->$columnName))
                                {
                                    $model_object->LAT_DeleteSpecificImage($model_object->$columnName);
                                }
                                if (!uploadFile($inputs[$column->Field], $model_object, $column->Field, 'laravel_admin_assets/' . $model_object->getTable() . '_images')) {
                                    DB::rollBack();
                                    return redirect()->back()->with('error', 'Something went wrong');
                                }
                            }
                        }elseif(checkDataTypeExists($column->Type, DB_DATATYPE_BOOLEAN))
                        {
                            // For Boolean Fields
                            if(isset($column->Field) && $inputs[$column->Field] == 1)
                            {
                                $model_object->$columnName = 1;
                            }else{
                                $model_object->$columnName = 0;
                            }
                        }elseif(checkDataTypeExists($column->Type, DB_DATATYPE_DATETIME))
                        {
                            $model_object->$columnName = Carbon::parse($inputs[$column->Field])->format('Y-m-d H:i:s');
                        }elseif(checkDataTypeExists($column->Type, DB_DATATYPE_DATE))
                        {
                            $model_object->$columnName = Carbon::parse($inputs[$column->Field])->format('Y-m-d');
                        }elseif(checkDataTypeExists($column->Type, DB_DATATYPE_TIMESTAMP))
                        {
                            $model_object->$columnName = Carbon::parse($inputs[$column->Field])->format('Y-m-d H:i:s');
                        }else
                        {
                            $model_object->$columnName = $inputs[$column->Field] ?? null;
                        }
                    }

                    if(!$model_object->save())
                    {
                        DB::rollBack();
                        return redirect()->back()->with('error', "Something went wrong");
                    }

                    DB::commit();
                    if($request->action == 'save_and_add'){
                        return redirect()->to('admin/crud/' . $crud_id . '/create')->with('success', 'The "' . $crud->model_singular_text . '" was saved successfully. You may add another ' . $crud->model_singular_text . ' below.');
                    }elseif($request->action == 'save_and_edit'){
                        return redirect()->to('admin/crud/' . $crud_id . '/' . $model_object->id . '/edit')->with('success', 'The "' . $crud->model_singular_text . '" was saved successfully. You may edit it again below.');
                    }else{
                        return redirect()->to('admin/crud/' . $crud_id)->with('success', 'The "' . $crud->model_singular_text . '" was saved successfully.');
                    }
                }else{
                    return redirect()->back()->with('error', $validation->message);
                }
            }else{
                return redirect()->back()->with('error', GENERAL_ERROR_MESSAGE);
            }


        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($crud_id, string $id)
    {
        $crud = getClassModelTitle($crud_id); // Get CRUD model cass, singular title and plural title
        $model_object = $crud->model_class::where('id', $id)->firstOrFail(); // create object of model cas
        $table_structure = $this->getTableStructure($crud_id);

        return view($this->default_view . 'create', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($crud_id, string $id)
    {
        $crud = getClassModelTitle($crud_id); // Get CRUD model cass, singular title and plural title
        $model_object = $crud->model_class::where('id', $id)->firstOrFail();
        $model_object->LAT_deleteImage($model_object);
        $model_object->delete();
        return true;
    }

    /*
        @getTableStructure
        * This method helps in create and edit method.
        * In this method we fetch all fields of table ordering by data types array
        * Then we display all fields in form when we create or edit.
        * This method purpose is reuseablity
    */
    private function getTableStructure($crud_id, &$crud = null)
    {
        $crud = getClassModelTitle($crud_id);
        $model_object = new $crud->model_class();
        $table_structure = $model_object->LAT_TableStructure(); // Complete Table Structure
        // All Data Types Array
        $data_types = getLaravelAdminDataTypesForCRUD();
        // table_structure_data Array for ordering the fields according to data types array
        $table_structure_data = [];
        foreach($table_structure as $col_key => $table_column)
        {
            $table_structure[$col_key]->enum_options = null;
            $table_structure[$col_key]->field_label = str_replace("_", " ", ucfirst($table_structure[$col_key]->Field));
            foreach($data_types as $data_type)
            {
                if(checkDataTypeExists($table_structure[$col_key]->Type, $data_type))
                {
                    if($data_type == DB_DATATYPE_ENUM)
                    {
                        // If data type of column is ENUM then append the enum options for select element in form
                        $table_structure[$col_key]->enum_options = getEnumOptions($table_structure[$col_key]->Type, $data_type);
                    }
                    // Append data type customized way in table column structure
                    $table_structure[$col_key]->data_type = $data_type;
                }
                if(checkImageColumnExists($table_structure[$col_key]->Field, $data_type))
                {
                    // If column exists with *_image then append data type is image
                    $table_structure[$col_key]->data_type = $data_type;
                }

            }
            // If data type is not appended then append default data type
            if(!isset($table_structure[$col_key]->data_type))
            {
                $table_structure[$col_key]->data_type = $table_structure[$col_key]->Type;
            }
        }

        // Get the seperated data type fields customization
        foreach($data_types as $data_type)
        {
            $table_structure_data[$data_type] = array_values(collect($table_structure)->where("data_type", $data_type)->toArray());
        }
        // Get customized data type fields data and update the table structure variable.
        $table_structure = [];
        foreach($table_structure_data as $data)
        {
            foreach($data as $columnTypeData)
            {
                $table_structure[] = $columnTypeData;
            }
        }
        // Here table structure will show fields in this order
        // ( varchar fields, integer fields, float fields,
        // text fields, enum select fields, boolean fields, text, longtext, timestamp fields)
        return $table_structure;
    }
}
