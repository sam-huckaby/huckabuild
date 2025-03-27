<?php

namespace Huckabuild\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'filename',
        'original_filename',
        'file_path',
        'file_size',
        'mime_type',
        'description'
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];
} 