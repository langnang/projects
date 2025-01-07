<?php

namespace App\Models;


class Link extends \Illuminate\Database\Eloquent\Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use \App\Traits\Model\HasFamily;
    use \App\Traits\Model\HasRelationship;
    use \App\Traits\Model\HasUser;
    /**
     * 与模型关联的数据表.
     *
     * @var string
     */
    protected $table = "links";
    /**
     * 模型的属性默认值。
     *
     * @var array
     */
    protected $attributes = [];
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