<?php

namespace App\Models;

class Meta extends \Illuminate\Database\Eloquent\Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    use \App\Traits\Model\HasFamily;
    use \App\Traits\Model\HasRelationship;
    protected $table = "metas";

    // protected $primaryKey = 'mid';

    protected $relationshipKey = "meta_id";

    protected $fillable = [
        'name',
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

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'release_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}