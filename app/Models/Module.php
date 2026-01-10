<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    //
    public function userAccesses()
    {
        return $this->hasMany(UserModuleAccess::class, 'module_id', 'module_id')->where('active', 1);;
    }
}
