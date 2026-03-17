<?php

namespace Bitsoftsol\LaravelAdministration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Bitsoftsol\LaravelAdministration\Models\AuthGroupPermission;
use Bitsoftsol\LaravelAdministration\Models\AuthUserGroup;

class AuthGroup extends Model
{
    use HasFactory;


    public function auth_group_permissions(){
        return $this->hasMany(AuthGroupPermission::class,'group_id');
    }

    public function auth_user_groups(){
        return $this->hasMany(AuthUserGroup::class);
    }

}
