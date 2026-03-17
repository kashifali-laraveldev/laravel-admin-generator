<?php

namespace Bitsoftsol\LaravelAdministration\Http\Controllers\Api\LaravelAdmin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class LaravelAdminApiController extends Controller
{
    /**
     * @models()
     * Crud the form for displaying the all models for laravel admin crud.
    */
    /**
     * @OA\GET(
     *      path="/api/admin/crud/models",
     *      operationId="list_of_crud_models",
     *      tags={"crud,models,listing"},
     *      summary="Crud Models Listing",
     *      security={
     *           {"bearerAuth": {}}
     *       },
     *      description="",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *       ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      )
    *     )
    */

    public function models()
    {
        try{
            $crud_list = API_getLaravelAdminCrudList();

            return successWithData(GENERAL_FETCH_MESSAGE, $crud_list);
        } catch (QueryException $e) {

            DB::rollBack();

            return error($e->getMessage(), ERROR_500);

        } catch (Exception $e) {

            DB::rollBack();

            return error($e->getMessage(), ERROR_500);
        }

    }
    /**
     * Display a listing of the resource.
     */
    public function index($crud_id, Request $request)
    {

        try{

            // Check Model ID is valid or not
            if(!API_validateModelID($crud_id))
            {
                DB::rollBack();

                return error(CRUD_MODEL_ID_NOT_VALID, ERROR_500);
            }

            $inputs = $request->all();

            $crud = API_getClassModelTitle($crud_id); // Get CRUD model cass, singular title and plural title

            $model_object = new $crud->model_class();

            $query = $crud->model_class::whereNotNull('id'); // create object of model cass

            if(isset($inputs['search']))
            {
                // Search from database table
                $query->where(function ($q) use ($inputs, $model_object) {

                    // Search keyword from fillable table fields
                    searchTable($q, $inputs['search'], $model_object->getFillable());

                });

            }

            // Get the final data
            $data = $query->get();

            // Get the all data and set the complete image Urls
            foreach($data as $key => $value)
            {
                // Get each data one by one and update all image_* columns with full url
                $data[$key] = $model_object->API_LAT_getImageAttribue($value);

            }

            return successWithData(GENERAL_FETCH_MESSAGE, $data);

        } catch (QueryException $e) {

            DB::rollBack();

            return error($e->getMessage(), ERROR_500);

        } catch (Exception $e) {

            DB::rollBack();

            return error($e->getMessage(), ERROR_500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

            // Check Model ID is valid or not
            if(!API_validateModelID($crud_id))
            {
                DB::rollBack();

                return error(CRUD_MODEL_ID_NOT_VALID, ERROR_500);
            }

            // Get CRUD model cass, singular title and plural title
            $crud = API_getClassModelTitle($crud_id);

            // Flag for checkding that data is updating or new adding.
            $isUpdating = false;

            if(isset($inputs['id']))
            {
                // Check ID for updating data is valid or not
                if(!$crud->model_class::whereId($inputs['id'])->first())
                {

                    // If any error in data storing then throw error
                    DB::rollBack();

                    return error(MODEL_OBJECT_ID_NOT_VALID_MESSAGE, ERROR_401);

                }

                $model_object = $crud->model_class::whereId($inputs['id'])->firstOrFail();

                // Flag for checkding that data is updating or new adding.
                $isUpdating = true;

            }else{
                $model_object = new $crud->model_class();
            }

            // Validate the all all required fields of table that are not nullable by default in db.
            $validation = $model_object->API_LAT_validate($inputs, $model_object);

            //if validation result is exists
            if(isset($validation->result))
            {
                if($validation->result == true)
                {
                    // Get Table Structure fields
                    $table_structure = $model_object->API_LAT_TableStructure();

                    // All table columns collection
                    $all_columns = collect($table_structure)
                        ->where('Field', '!=', DB_TABLE_PRIMARY_COLUMN)->toArray();

                    foreach($all_columns as $column)
                    {
                        $columnName = $column->Field;
                        if(checkImageColumnExists($column->Field, DB_DATATYPE_IMAGE))
                        {

                            // Validate Image Fields contains image files or not
                            if(isset($inputs[$columnName]) && !$request->hasFile($column->Field))
                            {

                                DB::rollBack();

                                return error(['The ' . $column->Field . ' must be an image file'], ERROR_401);

                            }

                            // For Image Fields
                            if($request->hasFile($column->Field))
                            {
                                if(isset($inputs['id']) && isset($model_object->$columnName))
                                {
                                    $model_object->API_LAT_DeleteSpecificImage($model_object->$columnName);
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
                            // If table fields are created at or updated at then auto insert now date.
                            if($columnName == 'created_at' || $columnName == 'updated_at')
                            {
                                // Get the formatted current date
                                $nowDate = Carbon::now()->format('Y-m-d H:i:s');

                                // If new data is adding then isUpdating will be false
                                if(!$isUpdating)
                                {
                                    // Store the created_at date
                                    $model_object->created_at = $nowDate;

                                }

                                // Store updated_at date when updating or adding new
                                $model_object->updated_at = $nowDate;

                            }else{

                                // Otherwise insert the date according to correct format
                                $model_object->$columnName = Carbon::parse($inputs[$column->Field])->format('Y-m-d H:i:s');

                            }

                        }else
                        {
                            // When no type is matched then insert same data into field without changing
                            $model_object->$columnName = $inputs[$column->Field] ?? null;
                        }
                    }

                    if(!$model_object->save())
                    {
                        // If any error in data storing then throw error
                        DB::rollBack();

                        return error(GENERAL_ERROR_MESSAGE, ERROR_401);
                    }

                    $model_object = $model_object->fresh();

                    $model_object->API_LAT_getImageAttribue($model_object);

                    // If all database transactions worked successfully then commit
                    DB::commit();

                    // Get the response in json with model data
                    return successWithData(GENERAL_SUCCESS_MESSAGE, $model_object);

                }else{
                    // If validation is failed then show the message in response with fail status
                    return error($validation->message, ERROR_401);

                }
            }else{

                // If any error in laravelAdminApi method API_LAT_Validate method.
                return error(GENERAL_ERROR_MESSAGE, ERROR_401);
            }


        } catch (QueryException $e) {

            // Catch Error in query
            DB::rollBack();

            // Error response
            return error($e->getMessage(), ERROR_401);

        } catch (Exception $e) {

            // Catch error and revert the db queries transactions
            DB::rollBack();

            // Throw the error in response.
            return error($e->getMessage(), ERROR_401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($crud_id, string $id)
    {
        try{

            DB::beginTransaction();

            // Check Model ID is valid or not
            if(!API_validateModelID($crud_id))
            {
                DB::rollBack();

                return error(CRUD_MODEL_ID_NOT_VALID, ERROR_500);
            }

            // Get CRUD model cass, singular title and plural title
            $crud = API_getClassModelTitle($crud_id);

            // Check ID for updating data is valid or not
            if(!$crud->model_class::whereId($id)->first())
            {

                // If any error in data storing then throw error
                DB::rollBack();

                return error(MODEL_OBJECT_ID_NOT_VALID_MESSAGE, ERROR_401);

            }

            $model_object = $crud->model_class::where('id', $id)->first();

            // Get image fileds with complete URL
            $model_object = $model_object->API_LAT_getImageAttribue($model_object);

            return successWithData(GENERAL_FETCH_MESSAGE, $model_object);

        } catch (QueryException $e) {

            // Catch Error in query
            DB::rollBack();

            // Error response
            return error($e->getMessage(), ERROR_401);

        } catch (Exception $e) {

            // Catch error and revert the db queries transactions
            DB::rollBack();

            // Throw the error in response.
            return error($e->getMessage(), ERROR_401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($crud_id, Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($crud_id, string $id)
    {
        try{

            // Begin the database transactions
            DB::beginTransaction();

            // Check Model ID is valid or not
            if(!API_validateModelID($crud_id))
            {
                DB::rollBack();

                return error(CRUD_MODEL_ID_NOT_VALID, ERROR_500);
            }

            // Get CRUD model cass, singular title and plural title
            $crud = API_getClassModelTitle($crud_id);

            // Check ID for updating data is valid or not
            if(!$crud->model_class::whereId($id)->first())
            {

                // If any error in data storing then throw error
                DB::rollBack();

                return error(MODEL_OBJECT_ID_NOT_VALID_MESSAGE, ERROR_401);

            }

            $model_object = $crud->model_class::where('id', $id)->first();

            // Delete the file if exists in model fields
            $model_object->API_LAT_deleteImage($model_object);

            // Delte instance of Model
            if($model_object->delete())
            {
                // If any error in data storing then throw error
                DB::commit();

                return success(GENERAL_DELETED_MESSAGE);
            }

            // In case of model object is not deleted then error message will be thrown here.
            DB::rollBack();

            return error(GENERAL_ERROR_MESSAGE, ERROR_401);

        } catch (QueryException $e) {

            // Catch Error in query
            DB::rollBack();

            // Error response
            return error($e->getMessage(), ERROR_401);

        } catch (Exception $e) {

            // Catch error and revert the db queries transactions
            DB::rollBack();

            // Throw the error in response.
            return error($e->getMessage(), ERROR_401);
        }

    }
}
