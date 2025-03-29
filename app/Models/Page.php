<?php

namespace Huckabuild\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';
    
    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
        'is_landing_page'
    ];

    public static function count()
    {
        return self::query()->count();
    }
} 