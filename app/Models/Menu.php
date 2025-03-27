<?php

namespace Huckabuild\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'is_active'
    ];

    public function items()
    {
        return $this->hasMany(MenuItem::class);
    }
} 