<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{

    /**
     * 与模型关联的数据表.
     *
     * @var string
     */
    protected $table = "logs";
    /**
     * 模型的属性默认值。
     *
     * @var array
     */
    protected $attributes = [];
    protected $fillable = [
        'instance',
        'channel',
        'level',
        'level_name',
        'message',
        'context',
        'remote_addr',
        'user_agent',
        'created_by',
        'created_at',
    ];

    protected $casts = [
        "context" => "array",
        'created_at' => 'datetime',
    ];
}