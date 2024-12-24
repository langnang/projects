<?php

namespace App\Models;


class Link extends \Illuminate\Database\Eloquent\Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use \App\Traits\Model\HasFamily;
    use \App\Traits\Model\HasRelationship;
    use \App\Traits\Model\HasUser;
    protected $table = "links";

    // protected $primaryKey = 'lid';
    protected $relationshipKey = "link_id";

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