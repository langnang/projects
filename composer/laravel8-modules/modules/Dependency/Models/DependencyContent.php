<?php

namespace Modules\Dependency\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DependencyContent extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Dependency\Database\factories\DependencyContentFactory::new();
    }
}
