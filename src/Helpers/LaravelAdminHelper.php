<?php

use Bitso\Models\LaravelAdminModel;
use Bitsoftsol\LaravelAdministration\Traits\LaravelAdmin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

/*
    @getAppModels
    // Get All Model files in Models directory
*/
function getAppModels()
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
    * Two Conditions : 1-> Check whether Model class is using LaravelAdmin Trait or not.
    * 2-> Check whether Model class contains the migrated table into PhpMyAdmin
    * make model class path as App\Models\ModelName
*/
function getLaravelAdminModels()
{
    // Fetch all model classes in Models directory with Complete path without extension
    $appModels = getAppModels();
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

        if(in_array(LaravelAdmin::class,
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
    @getAppModelList
    * All App Models in Model Directory
*/
function getAppModelList()
{
    // Fetch all model classes in Models directory with Complete path without extension
    $appModels = getAppModels();
    $modelList = [];
    $index = 0;
    foreach($appModels as $mKey => $model)
    {
        $is_trait_used = 0;

        $model_file_path = $model;
        // If OS is windows then Back slash, Otherwise Forward Slash
        if(getSlashAboutOS() == SLASH_WINDOWS_FOR_FILE_PATH)
        {
            $model_file_path = str_replace('/', "\\", $model);
        }
        $model_file_path = $model_file_path . ".php";

        // Model Class Find krney k lye Replacement
        // Replace the absolute path to the application with an empty string
        $model = str_replace(app_path(), '', $model);

        // Convert path to namespace, taking care of directory separator
        $model = str_replace('/', '\\', $model);

        // Prefix the namespace
        $model = "App" . $model;

        $model_object = new $model(); // create object of model cass
        $tableName = $model_object->getTable();
        $model_name = class_basename($model_object);

        $modelClass = new $model();
        $table_name = $modelClass->getTable();
        if(in_array(LaravelAdmin::class,
        class_uses_recursive($model),
        true))
        {
            // If trait is used then flag is 1
            $is_trait_used = 1;
        }
        $modelList[$index]["model"] = $model;
        $modelList[$index]["table_name"] = $table_name;
        $modelList[$index]["is_trait_used"] = $is_trait_used;
        $modelList[$index]["model_name"] = $model_name;
        $modelList[$index]["model_file_path"] = $model_file_path;

        $index++;
    }
    return $modelList;
}

/*
    getLaravelAdminCrudList()
        Get list of all models that contains LaravelAdmin Trait and Migrated
*/

function getLaravelAdminCrudList()
{
    $modelList = getLaravelAdminModels();
    $crud_list = [];
    foreach($modelList as $model)
    {
        $model_singular = explode("\\", $model)[2];
        $model_plural = getPlural($model_singular);
        $crud_list[$model_plural] = $model . '-' . $model_singular . '-' . $model_plural;
    }

    return $crud_list;
}

/*
    getLaravelAdminCrudList()
        Get list of all models that contains LaravelAdmin Trait and Migrated
*/

function getCrudSchemaList()
{
    $modelList = getAppModelList();
    $schema_model_list = [];
    $index = 0;
    foreach($modelList as $modelKey => $modelValue)
    {
        $model = $modelValue['model'];
        $model_singular = explode("\\", $model)[2];
        $model_plural = getPlural($model_singular);
        $schema_model_list[$index]['model_name'] = $model_singular;
        $schema_model_list[$index]['table_name'] = $modelValue['table_name'];
        $schema_model_list[$index]['is_trait_used'] = $modelValue['is_trait_used'];
        $schema_model_list[$index]['detail'] = $model . '-' . $model_singular . '-' . $model_plural;
        $index++;
    }
    return $schema_model_list;
}

/*
    getPlural()
        Pass the singular word and pass count greater than one, then you will get plural of word
*/
function getPlural($phrase, $value = 2)
{
    $plural='';
    if($value>1){
        for($i=0;$i<strlen($phrase);$i++){
            if($i==strlen($phrase)-1){
                $plural.=($phrase[$i]=='y')? 'ies':(($phrase[$i]=='s'|| $phrase[$i]=='x' || $phrase[$i]=='z' || $phrase[$i]=='ch' || $phrase[$i]=='sh')? $phrase[$i].'es' :$phrase[$i].'s');
            }else{
                $plural.=$phrase[$i];
            }
        }
        return $plural;
    }
    return $phrase;
}

/*
    getClassModelTitle()
        $type is encoded string, in this string class of model, singular name of model and plural name of
        model exists. we explode with hyphen to get individuals.
*/
function getClassModelTitle($type)
{
    $res = [];
    $model = explode("-", base64_decode($type));
    $res['model_class'] = $model[0];
    $res['model_singular_text'] = $model[1];
    $res['model_plural_text'] = $model[2];

    return (object) $res;
}

/*
    @checkDataTypeExists
    * If data type word exists in string then return true otherwise return false.
*/
function checkDataTypeExists($data_type_string, $data_type)
{
    if (strpos($data_type_string, $data_type) !== false)
    {
        return true;
    }
    return false;
}

/*
    @checkDataTypeExists
    * If data type word exists in string then return true otherwise return false.
*/
function checkStringExitsInContent($data_type_string, $data_type)
{
    if (strpos($data_type_string, $data_type) !== false)
    {
        return true;
    }
    return false;
}

/*
    @checkImageColumnExists
        Use a consistent naming convention for columns intended to store image paths
        or URLs. For example, any column with a name like _image, _img,
        or image_* could be treated as an image column.
*/
function checkImageColumnExists($data_type_string, $data_type)
{
    if (strpos($data_type_string, $data_type) !== false)
    {
        return true;
    }
    return false;
}

/*
    @getEnumOptions
    * Get enum options from table column defined structure.
    * enum ('Male', 'Female') :=> Fetch Male,Female as array for Options
*/
function getEnumOptions($column)
{
    return str_replace("'", '', explode(",", explode(")", explode("(", $column)[1])[0]));;
}

/*
    @UploadFile
    Upload file get the model and column name where path will be saved and folder name in which
    image will be uploaded
*/
function uploadFile($file, $model, $column, $folderTitle = 'laravel-admin-images' )
{
    $folderName = $folderTitle;
    // make file path structure
    $filePath = $folderName . '/' . date('Y') . '/' . date('m') . '/';
    //Set public folder path
    //renaming the file
    $name = $column . '_' . time() . '_' . rand(5000, 100000) . "." . $file->getClientOriginalExtension();
    if (env('AWS_ENV')) {
        Storage::disk('s3')->putFileAs($filePath, $file, $name);
    } else {
        $folderPath = public_path('/') . $filePath;
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        if (!$file->move($folderPath, $name)) {
            return false;
        }
    }
    $model->{$column} = $filePath . $name;
    return true;
}


/*
    @getLaravelAdminDataTypes
    Return the data types
*/
function getLaravelAdminDataTypes()
{
    $data_types = [
        // Order Of Form Fields
        DB_DATATYPE_VARCHAR => 'string',
        DB_DATATYPE_INTEGER => 'integer',
        DB_DATATYPE_BIG_INTEGER_TEXT => 'bigInteger',
        DB_DATATYPE_FLOAT => 'float',
        DB_DATATYPE_ENUM => 'enum',
        DB_DATATYPE_BOOLEAN => 'boolean',
        DB_DATATYPE_TEXT => 'text',
        DB_DATATYPE_LONGTEXT => 'longText',
        DB_DATATYPE_DATE => 'date',
        DB_DATATYPE_DATETIME => 'datetime',
        DB_DATATYPE_TIMESTAMP => 'timestamp',
        DB_DATATYPE_IMAGE => 'Image',

    ];
    return $data_types;
}

function getLaravelAdminDataTypesForCRUD()
{
    $data_types = [
        // Order Of Form Fields
        DB_DATATYPE_VARCHAR,
        DB_DATATYPE_INTEGER,
        DB_DATATYPE_BIG_INTEGER,
        DB_DATATYPE_FLOAT,
        DB_DATATYPE_ENUM,
        DB_DATATYPE_BOOLEAN,
        DB_DATATYPE_TEXT,
        DB_DATATYPE_LONGTEXT,
        DB_DATATYPE_DATE,
        DB_DATATYPE_DATETIME,
        DB_DATATYPE_TIMESTAMP,
        DB_DATATYPE_IMAGE,

    ];
    return $data_types;
}


function getSlashAboutOS(){
    if(php_uname('s') == "Windows NT")
    {
        return "\\";
    }else{
        return "/";
    }
}

/*
    @getAppMigrationFiles
    // Find the migrations directory and fetch all migration files
*/
function getAppMigrationFiles()
{
    // Find folder named as migrations
    // Get All models in Models Directory
    $path = base_path("database" . getSlashAboutOS() . "migrations");
    $out = [];

    $results = scandir($path);
    $index = 0;
    foreach ($results as $result) {
        if ($result === '.' or $result === '..') continue;
        // Change slash forward when os is MAC
        $migration_file_name = $result;
        $migration_db_name = explode(".php", $result)[0];

        $filename = $path . getSlashAboutOS() . $result;

        if (is_dir($filename)) {
            $out = array_merge($out, getAppModels($filename));
        }else{
            $outFilePath = substr($filename,0,-4);
            $outFilePath = str_replace("_table", "_table.php", $outFilePath);
            $table_name = explode("_table.php", $outFilePath);
            $table_name = explode("create_", $table_name[0]);
            $out[$index]["file_path"] = $outFilePath;
            $out[$index]["table_name"] = $table_name[1];
            $out[$index]["migration_file_name"] = $migration_file_name;
            $out[$index]["migration_db_name"] = $migration_db_name;
            $index++;
        }
    }

   // list of migration files with complete path and table name
    return $out;
}

function getIgnoredModelNames()
{
    $excluded_models = [
        "AppModel",
        "AuthGroup",
        "AuthGroupPermission",
        "AuthPermission",
        "AuthUserGroup",
        "User",
        "LaravelAdminModel"
    ];

    return $excluded_models;
}

/*
    @listOfNewModels
        List of New Models
*/

function listOfNewModels()
{
    $ignoredList = getIgnoredModelNames();
    $appModels = getAppModelList();
    $newModels = [];

    foreach($appModels as $model)
    {
        $migration_files = getAppMigrationFiles();
        if(!in_array($model['model_name'], $ignoredList))
        {
            // Get Migration File Path
            $migration_file_path = collect($migration_files)->where('table_name', $model['table_name'])->value('file_path');

            // Migration File Name with .php extensiton
            $model['migration_file_name'] = collect($migration_files)->where('table_name', $model['table_name'])->value('migration_file_name');

            // Migration File name without .php extenstion
            $model['migration_db_name'] = collect($migration_files)->where('table_name', $model['table_name'])->value('migration_db_name');

            $model['migration_file_path'] = isset($migration_file_path) ? $migration_file_path : null ;
            $model['is_schema_created'] = 0 ;
            $model['is_schema_migrated'] = 0 ;
            // Check table is migrated or not
            if(Schema::hasTable($model['table_name']))
            {
                $model['is_schema_migrated'] = 1;
            }
            if(isset($model['migration_file_path']))
            {
                $fileContent = file_get_contents($model['migration_file_path']);
                $needle = '$table->';
                $countTableArrow = substr_count($fileContent,$needle);
                if($countTableArrow > 2)
                {
                    $model['is_schema_created'] = 1 ;
                }
            }
            $model['detail'] = $model['model_name'] . '-' . $model['table_name'] . '-' . $model['model_file_path'];
            $newModels[] = $model;
        }
    }
    return $newModels;
}

/*
    @deleteModelMigrationTable
        Delete Particular Model and Migration File
*/
function deleteModelMigrationTable($model)
{
    // If file exists then delete the files from the defined path.
    if(File::exists($model['model_file_path']))
    {
        File::delete($model['model_file_path']);
    }
    if(File::exists($model['migration_file_path']))
    {
        File::delete($model['migration_file_path']);
    }

    return ['result' => true, "message" => "Schema removed successfully"];
}

/*
    @deleteAllModelAndMigrationFiles
        Delete All Model
*/
function deleteAllModelAndMigrationFiles()
{
    $newModels = listOfNewModels();
    foreach($newModels as $model)
    {
        if(File::exists($model['model_file_path']))
        {
            File::delete($model['model_file_path']);
        }
        if(File::exists($model['migration_file_path']))
        {
            File::delete($model['migration_file_path']);
        }
        if(Schema::hasTable('migrations'))
        {
            if(DB::table('migrations')->where('migration', $model['migration_db_name'])->count() > 0)
            {
                DB::table('migrations')->where('migration', $model['migration_db_name'])->delete();
            }
        }

        Schema::dropIfExists($model['table_name']);
    }

    return ['result' => true, "message" => "All new model and migration files deleted successfully"];
}

/*
    @decryptMyModelSchema
    decrypt the encoded string
*/
function decryptMyModelSchema($schema_model)
{
    return json_decode(base64_decode($schema_model));
}

// @checkSyntaxError
// Check Syntax Error in PHp File
function isAnySyntaxError($file_path)
{
    if(File::exists($file_path))
    {
        $fileName = $file_path;
        exec("php -l {$fileName}", $output, $return);

        if ($return === 0) {
            return false;
        } else {
            return true;
        }
    }
    return false;

}

/*
    @LAH_simpelDataTypes()
*/
function LAH_simpelSchemaDataTypes()
{
    // This data type list is, when User create schema on portal and select data type for column
    return $data_types = [
        // Order Of Form Fields
        DB_DATATYPE_VARCHAR => 'string',
        DB_DATATYPE_INTEGER => 'integer',
        DB_DATATYPE_BIG_INTEGER_TEXT => 'bigInteger',
        DB_DATATYPE_FLOAT => 'float',
        // DB_DATATYPE_ENUM => 'enum',
        DB_DATATYPE_BOOLEAN => 'boolean',
        DB_DATATYPE_TEXT => 'text',
        DB_DATATYPE_LONGTEXT => 'longText',
        DB_DATATYPE_DATE => 'date',
        DB_DATATYPE_DATETIME => 'datetime',
        DB_DATATYPE_TIMESTAMP => 'timestamp',
        // DB_DATATYPE_IMAGE => 'Image',

    ];
}


/*
    @getMigrationFileName
        filePath is parameter
*/
function getMigrationFileName($filePath)
{
    // Get migration File name here.
    return $migration_file_name = explode(getSlashAboutOS() . "database" . getSlashAboutOS() . "migrations" . getSlashAboutOS(), $filePath)[1];
}
