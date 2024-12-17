<?php

namespace App\Models;

class Relationship extends \Illuminate\Database\Eloquent\Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $table = "relationships";

    protected $fillable = [];

    protected $casts = [];
    public $timestamps = false;
}