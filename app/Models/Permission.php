<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['role_id','module_id','can_view','can_create','can_edit','can_delete'];
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'module_id');
    }
}
