<?php

namespace Bitsoftsol\LaravelAdministration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Bitsoftsol\LaravelAdministration\Models\AppModel;

class AuthPermission extends Model
{
    use HasFactory;

    protected $fillable = ['app_model_id', 'name', 'codename'];

    public function app_model()
    {
        return $this->belongsTo(AppModel::class);
    }

    public function auth_group_permissions(){
        return $this->hasMany(AuthGroupPermission::class);
    }
}
