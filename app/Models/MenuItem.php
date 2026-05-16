<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = ['title','target','url','parent_id','order_index','is_main','is_footer','type', 'active', 'created_by'];
    protected $casts = [
        'is_main'   => 'boolean',
        'is_footer' => 'boolean',
    ];
    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->where('active',1)->orderBy('order_index');
    }
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
        //return $this->children()->where('active', 1)->with('childrenRecursive');
    }
    public function document()
    {
        return $this->hasOne(Document::class, 'parent_menu_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function getMenuTypeLabelAttribute(): string
    {
        if ($this->is_main && $this->is_footer) {
            return 'Main Menu & Footer Menu';
        }
        if ($this->is_main) {
            return 'Main Menu';
        }
        if ($this->is_footer) {
            return 'Footer Menu';
        }
        return '—';
    }
    public function getPageTypeLabelAttribute(): string
    {
        if ($this->type=='1') {
            return 'Document';
        }
        if ($this->type =='2') {
            return 'Custom Page';
        }
        return '—323wesfdsf';
    }
    public static function menuTypeOptions(): array
    {
        return [
            1 => 'Main Menu',
            2 => 'Footer Menu',
            3 => 'Main Menu & Footer Menu',
        ];
    }
    public function scopeMenuType($query, ?int $type = null)
    {
        if (! $type) return $query;
        return match ($type) {
            1 => $query->where('is_main', 1),
            2 => $query->where('is_footer', 1),
            3 => $query->where('is_main', 1)->where('is_footer', 1),
        };
    }
    public function getMenuTypeAttribute(): ?int
    {
        return match (true) {
            $this->is_main && $this->is_footer => 3,
            $this->is_main => 1,
            $this->is_footer => 2,
            default => null,
        };
    }
}
