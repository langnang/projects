<?php

namespace App\Models;

class Comment extends \Illuminate\Database\Eloquent\Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $table = "_comments";

    protected $primaryKey = 'coid';

    protected $fillable = [];

}