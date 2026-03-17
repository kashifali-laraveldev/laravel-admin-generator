<?php

namespace Bitsoftsol\LaravelAdministration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Bitsoftsol\LaravelAdministration\Models\AuthGroup;
use Bitsoftsol\LaravelAdministration\Models\User;

class AuthUserGroup extends Model
{
    use HasFactory;

    public function auth_group()
    {
        return $this->belongsTo(AuthGroup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
