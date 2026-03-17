<?php
namespace Bitsoftsol\LaravelAdministration\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

trait LaravelAdminAPI{

    /*
        @LAT_TableStructure
        * Fetch all fields of table with All description of each column in database
        * Column Contains Detail Like :=> "Field name", "Type", "Null", "Key", "Default", "Extra"
    */
    function API_LAT_TableStructure()
    {
        return DB::select('describe ' . $this->getTable());
    }

    /*
        @LA_validateAllInputs
        * Get all fields of table and validate in form inputs
        * Column Contains Detail Like :=> "Field name", "Type", "Null", "Key", "Default", "Extra"
    */

    function API_LAT_validate($inputs, $model_object)
    {
        $table_structure = $this->LAT_TableStructure();
        $required_columns_message = [];
        $required_columns = collect($table_structure)
            ->where('Field', '!=', DB_TABLE_PRIMARY_COLUMN)
            ->where('Null', NULL_NO)
            ->pluck('Field')->toArray();

        foreach($required_columns as $key => $req_column)
        {

            // If We are editing and image is already exists then it will be optional on Edit
            if(isset($inputs['model_object_id']) &&
                checkImageColumnExists($req_column, DB_DATATYPE_IMAGE) &&
                isset($model_object->$req_column))
            {
                // If image exists then continue;
                continue;
            }
            // Add Validation Column must be file

            if(!isset($inputs[$req_column]))
            {

                $errorMsg = "The " . $req_column . " field is required";

                array_push($required_columns_message, $errorMsg);
            }
        }

        if(count($required_columns_message) > 0)
        {

            // Result is false
            return (object) ['result' => false, "message" => ($required_columns_message) ];

        }

        // Result true , fields are validated
        return (object) ['result' => true, "message" => ''];
    }

    function API_LAT_DeleteSpecificImage($file)
    {
        if(File::exists($file))
        {
            File::delete($file);
        }
        return true;
    }

    /*
        @LAT_dataToStore
        * Make data to store in database.
    */

    function API_LAT_deleteImage($model_object)
    {
        $table_structure = $this->LAT_TableStructure();
        $all_columns = collect($table_structure)
            ->where('Field', '!=', DB_TABLE_PRIMARY_COLUMN)
            ->pluck('Field')->toArray();
        foreach($all_columns as $column)
        {

            // If We are editing and image is already exists then it will be optional on Edit
            if(checkImageColumnExists($column, DB_DATATYPE_IMAGE) &&
                isset($model_object->$column))
            {
                if(File::exists($model_object->$column))
                {
                    File::delete($model_object->$column);
                }
            }
        }

    }

    /*
        @API_LAT_getImageAttribue
        * get the exact path of image fields.
    */

    function API_LAT_getImageAttribue(&$model_object)
    {
        $table_structure = $this->LAT_TableStructure();
        $image_columns = [];
        $all_columns = collect($table_structure)
            ->where('Field', '!=', DB_TABLE_PRIMARY_COLUMN)
            ->pluck('Field')->toArray();
        foreach($all_columns as $column)
        {
            // If We are editing and image is already exists then it will be optional on Edit
            if(checkImageColumnExists($column, DB_DATATYPE_IMAGE) &&
                isset($model_object->$column))
            {
                if(File::exists($model_object->$column))
                {
                    // dd("Fine");
                    $image_columns[] = $column;
                    $model_object->$column = url($model_object->$column);
                }else{
                    $model_object->$column = url(DEFAULT_IMAGE_PATH);
                }
            }
        }
        // dd($model_object);
        return $model_object;

    }

    /*
        @API_LAT_getImageColumnNames
        * get the all column names list that contains image_* -  name.
    */

    function API_LAT_getImageColumnNames(&$model_object)
    {
        $table_structure = $this->LAT_TableStructure();
        $image_column_names = [];
        $all_columns = collect($table_structure)
            ->where('Field', '!=', DB_TABLE_PRIMARY_COLUMN)
            ->pluck('Field')->toArray();
        foreach($all_columns as $column)
        {
            // If We are editing and image is already exists then it will be optional on Edit
            if(checkImageColumnExists($column, DB_DATATYPE_IMAGE))
            {
                $image_column_names[] = $column;
            }
        }

        return $image_column_names;

    }

}

