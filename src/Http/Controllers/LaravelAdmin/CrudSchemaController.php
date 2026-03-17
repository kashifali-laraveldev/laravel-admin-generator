<?php

namespace Bitsoftsol\LaravelAdministration\Http\Controllers\LaravelAdmin;

use App\Http\Controllers\Controller;
use Bitsoftsol\LaravelAdministration\Models\LaravelAdminModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Throwable;

class CrudSchemaController extends Controller
{
    /*
        * Constructor is here, we are storing the reuseable values here.
    */
    private $default_view, $base_folder;
    public function __construct()
    {
        $this->default_view = 'laravel-admin::laravel_admin.crud_schema.';
        $this->base_folder = 'laravel-admin::laravel_admin.';
    }

    /**
     * @list
     * Display list of models
    */
    public function list()
    {
        $schema_list = listOfNewModels();
        return view($this->default_view . "list_schema", get_defined_vars());
    }

    /**
     * @create
     * Display page to create model and Migrations
    */
    public function create()
    {
        return view($this->default_view . "create_schema");
    }

    /**
     * @createModel
     * Artisan command to create model and migration
    */
    public function createModel(Request $request)
    {
        try{
            $inputs = $request->all();
            Artisan::call('make:model ' . $inputs['model_name'] . ' -m');

            return redirect()->to('admin/crud-schema')->with('success', "Model and migration created successfully");
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return view($this->default_view . "create_schema");
    }

    /**
     * @createModel
     * Artisan command to create model and migration
    */
    public function migrate($schema_model_id, Request $request)
    {
        try{
            $inputs = $request->all();
            $schema_model = decryptMyModelSchema($schema_model_id);

            // For any syntax Error in Model or Migration File
            if(isAnySyntaxError($schema_model->migration_file_path))
            {
                return back()->with('error', "Syntax error in migration file");
            }
            if(isAnySyntaxError($schema_model->model_file_path))
            {
                return back()->with('error', "Syntax error in model file");
            }

            // Migrate The Single Migration File
            Artisan::call('migrate --path=database/migrations/' . $schema_model->migration_file_name);

            return redirect()->to(PREFIX_ADMIN_FOR_ROUTES .  'crud-schema')->with('success', 'Migration run successfully');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return view($this->default_view . "create_schema");
    }

    /**
     * @store
     * Store table fields in migration file
    */
    public function store($schema_model, Request $request)
    {
        try{
            $schema_model = decryptMyModelSchema($schema_model);
            $inputs = $request->all();
            // Model Write
            $model_write = [];
            $model_write['four_spaces'] = "    ";
            $model_write['filename'] = $schema_model->model_file_path;
            $model_write['search'] = 'use HasFactory;';
            $model_write['laravel_admin_trait'] = "use LaravelAdmin;";
            $model_write['search_laravel_admin_trait'] = "\n" . "use LaravelAdmin;";
            $model_write['search_import_model'] = 'namespace App\Models;' . "\n";
            $model_write['import_laravel_admin'] = "\n" . 'use Bitsoftsol\LaravelAdministration\Traits\LaravelAdmin;';
            $model_write['get_file_content'] = file_get_contents($schema_model->model_file_path);
            $model_write = (object) $model_write;

            if(isset($inputs['is_laravel_admin_used']))
            {
                // Replace use LaravelAdmin;
                $replace = $model_write->search . "\n" . $model_write->four_spaces . $model_write->laravel_admin_trait;
                $updated_file_content = str_replace($model_write->search, $replace, $model_write->get_file_content);
                // Replace Import Trait on Top
                $replace = $model_write->search_import_model . $model_write->import_laravel_admin;
                $updated_file_content = str_replace($model_write->search_import_model, $replace, $updated_file_content);

                // Finally Write Content in File
                file_put_contents($model_write->filename, $updated_file_content);

            }

            // Migration Write
            $migration_write = [];
            $migration_write['twelve_spaces'] = "            ";
            $migration_write['filename'] = $schema_model->migration_file_path;
            $migration_write['search'] = '$table->id();';
            $migration_write['get_file_content'] = file_get_contents($schema_model->migration_file_path);
            $migration_write['table_fields'] = '';
            $migration_write = (object) $migration_write;

            foreach($inputs['column_name'] as $fieldKey => $field)
            {
                // Migration Schema
                $migration_write->table_fields .= $migration_write->twelve_spaces . '$table->' . $inputs["data_type"][$fieldKey] . '("'. $field . '")';
                if(isset($inputs["default_null"][$fieldKey]) && $inputs["default_null"][$fieldKey] == "1")
                {
                    $migration_write->table_fields .= '->nullable()';
                }
                if(isset($inputs["default_value"][$fieldKey]))
                {
                    if(isset($inputs["data_type"][$fieldKey]) && $inputs["data_type"][$fieldKey] != DB_DATATYPE_BOOLEAN_TEXT)
                    {
                        $migration_write->table_fields .= '->default("' . $inputs["default_value"][$fieldKey] . '")';
                    }else{
                        $migration_write->table_fields .= '->default(1)';
                    }

                }
                // End line of code on semi colon
                $migration_write->table_fields .= ';';
                if(($fieldKey+1) < count($inputs['column_name']))
                {
                    // Move to next line
                    $migration_write->table_fields .= "\n";
                }
            }

            // Append Content After Search and Replace
            $replace = $migration_write->search . "\n" . $migration_write->table_fields;
            $updated_file_content = str_replace($migration_write->search, $replace, $migration_write->get_file_content);

            // Finally Write Content in Migration File
            file_put_contents($migration_write->filename, $updated_file_content);

            return redirect()->to(PREFIX_ADMIN_FOR_ROUTES .  'crud-schema')->with('success', $schema_model->model_name . " Schema created successfully");
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * @edit
     * Edit the created model and migration schema and trait used or not
    */
    public function edit($schema_model_id)
    {
        $schema_data_types = LAH_simpelSchemaDataTypes();
        $schema_model = decryptMyModelSchema($schema_model_id);
        $schema_model->schema_model_id = $schema_model_id;

        return view($this->default_view . "edit_schema", get_defined_vars());
    }

    /**
     * @editor
     * VS Code Editor to edit the Model and Migration Files
    */
    public function editor($schema_model_id, Request $request)
    {
        try{
            $inputs = $request->all();
            $schema_model = decryptMyModelSchema($schema_model_id);
            $file_title = "Migration";
            $file_path = $schema_model->migration_file_path;
            if(isset($inputs['open']))
            {
                $file_title = "Model";
                $file_path = $schema_model->model_file_path;
                $file_content = file_get_contents($schema_model->model_file_path);
            }else{
                $file_content = file_get_contents($schema_model->migration_file_path);
            }

            return view($this->default_view . "editor_schema", get_defined_vars());
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    /**
     * @saveEditor
     * VS Code Editor when click on save file button
    */
    public function saveEditor($schema_model_id, Request $request)
    {
        try{
            // Validate the request data
            $request->validate([
                'content' => 'required|string',
                'file_path' => 'required',
                'editor_name' => 'required',
            ]);
            $inputs = $request->all();

            $schema_model = decryptMyModelSchema($schema_model_id);

            // Make a backup of the original file, if desired
            // File::copy($schema_model->model_file_path, $schema_model->model_file_path . ".bak");

            // Save the edited content
            File::put($inputs['file_path'], $inputs['content']);
            return response()->json(['result' => true, 'message' => $inputs['editor_name'] . ' file saved successfully!']);

        }catch (QueryException $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * @delete
     * Edit the created model and migration schema and trait used or not
    */
    public function delete($schema_model)
    {
        try{
            $schema_model = (array) json_decode(base64_decode($schema_model));
            // Run the rollback method of migration
            Artisan::call('migrate:rollback --path=database/migrations/' . $schema_model['migration_file_name']);
            // Delete Model and Migration Files
            $res = deleteModelMigrationTable($schema_model);
            return back()->with("success", "Success");
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }



}
