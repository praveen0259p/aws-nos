<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModuleAccess extends Model
{
    protected $table = 'user_module_access';
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'module_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
