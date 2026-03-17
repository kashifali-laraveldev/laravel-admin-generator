<?php

namespace Bitsoftsol\LaravelAdministration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Bitsoftsol\LaravelAdministration\Models\AuthPermission;

class AppModel extends Model
{
    use HasFactory;

    protected $fillable = ['model'];
    public function auth_permissions()
    {
        return $this->hasMany(AuthPermission::class);
    }

}
