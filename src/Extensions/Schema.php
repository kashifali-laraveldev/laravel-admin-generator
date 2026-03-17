<?php
namespace App\Extensions;

use App\Models\AppModel;
use App\Models\AuthPermission;
use Illuminate\Support\Facades\Schema as BaseSchema;

class Schema extends BaseSchema {
    public static function rename($from, $to) {
        parent::rename($from, $to);
        $app_model = AppModel::where('model', $from)->first();
        if($app_model) {
            $app_model->update(['model' => $to]);
            $permissions = ['add', 'change', 'view', 'delete'];
            foreach ($permissions as $key => $permission) {
                $new_codename = "{$permission}_{$to}";
                $new_name = "Can {$permission} {$to} entry";
                AuthPermission::where([
                    'app_model_id' => $app_model->id,
                ])->update(['name'=> $new_name, 'codename' => $new_codename]);
            }
        }
    }
}
