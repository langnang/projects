<?php

namespace App\Models;

class File extends \Illuminate\Database\Eloquent\Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $table = "files";

    // protected $primaryKey = 'coid';

    protected $fillable = [
        'slug',
        'name',
        'extension',
        'path',
        'type',
        'status',
        'user',
        'template',
        'views',
        'parent',
        'count',
        'order',
        'created_at',
        'updated_at',
        'release_at',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'release_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}