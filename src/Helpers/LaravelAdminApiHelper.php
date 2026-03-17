<?php

use Illuminate\Support\Facades\Schema;
use Bitsoftsol\LaravelAdministration\Traits\LaravelAdminAPI;

function API_getLaravelAdminCrudList()
{
    $modelList = API_getLaravelAdminModels();
    $crud_list = [];
    foreach($modelList as $key => $model)
    {
        $model_singular = explode("\\", $model)[2];
        $model_plural = getPlural($model_singular);
        // dd($model);
        $crud_list[$key]['model_id'] = base64_encode($model_singular);
        $crud_list[$key]['model_name'] = $model_singular;
    }

    return $crud_list;
}

/**
 * @getLaravelAdminModels
    * Two Conditions : 1-> Check whether Model class is using LaravelAdmin Trait or not.
    * 2-> Check whether Model class contains the migrated table into PhpMyAdmin
    * make model class path as App\Models\ModelName
*/
function API_getLaravelAdminModels()
{
    // Fetch all model classes in Models directory with Complete path without extension
    $appModels = API_getAppModels();
    $modelList = [];
    foreach($appModels as $model)
    {
        // Replace the absolute path to the application with an empty string
        $model = str_replace(app_path(), '', $model);

        // Convert path to namespace, taking care of directory separator
        $model = str_replace('/', '\\', $model);

        // Prefix the namespace
        $model = "App" . $model;
        $model_object = new $model(); // create object of model cass

        $tableName = $model_object->getTable();

        if(in_array(LaravelAdminAPI::class,
        class_uses_recursive($model),
        true) && Schema::hasTable($tableName))
        {
            // Add LaravelAdmin Model into modelList
            $modelList[] = $model;
        }
    }
    return $modelList;
}


/*
    @getAppModels
    // Get All Model files in Models directory
*/
function API_getAppModels()
{
    // Find folder named as Models
    $path = app_path() . "/Models";
    // dd($path);
    // Get All models in Models Directory
    $out = [];
    $results = scandir($path);
    foreach ($results as $result) {
        if ($result === '.' or $result === '..') continue;
        $filename = $path . '/' . $result;
        if (is_dir($filename)) {
            $out = array_merge($out, getAppModels($filename));
        }else{
            $out[] = substr($filename,0,-4);
        }
    }
    // Here is all list of model classes in "out" array that exists in Models directory
    return $out;
}

/*
    getClassModelTitle()
        $type is encoded string, in this string class of model, singular name of model and plural name of
        model exists. we explode with hyphen to get individuals.
*/
function API_getClassModelTitle($type)
{
    $res = [];
    $model = base64_decode($type);
    $model_class = 'App\\Models\\' . $model;
    $res['model_class'] = $model_class;
    $res['model_singular_text'] = $model;
    $res['model_plural_text'] = getPlural($model);

    return (object) $res;
}


/**
 * Commented Function
 * model_id
 *
 */
function API_validateModelID($model_id)
{
    // Crud LaravelAdminAPI models List
    $list = API_getLaravelAdminCrudList();

    // Pluck Model IDs to match
    $model_ids = collect($list)->pluck('model_id')->toArray();

    if(in_array($model_id, $model_ids))
    {

        return true;

    }

    return false;
}

// Search Data from database tables
function searchTable($query, $keyword, $filters, $with = null)
{
    if ($with) {
        $query->orWhereHas($with, function ($q) use ($filters, $keyword) {
            foreach ($filters as $key => $column) {
                if ($key == 0) {
                    $q->where($column, 'LIKE', '%' . $keyword . '%');
                } else {
                    $q->orWhere($column, 'LIKE', '%' . $keyword . '%');
                }
            }
        });
    } else {
        foreach ($filters as $key => $column) {
            $query->orWhere($column, 'LIKE', '%' . $keyword . '%');
        }
    }

    return $query;
}
