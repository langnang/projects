<?php

namespace App\Models;

class Comment extends \Illuminate\Database\Eloquent\Model
{
    protected $table = "_comments";

    protected $primaryKey = 'coid';

    protected $fillable = [];

}