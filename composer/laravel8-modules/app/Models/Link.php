<?php

namespace App\Models;


class Link extends \Illuminate\Database\Eloquent\Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $table = "_links";

    protected $primaryKey = 'lid';

    protected $fillable = [
        'title',
        'slug',
        'ico',
        'description',
        'type',
        'status',
        'parent',
        'count',
        'order',
        'created_at',
        'updated_at',
        'release_at',
        'deleted_at',
    ];

}