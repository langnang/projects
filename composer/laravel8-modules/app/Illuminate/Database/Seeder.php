<?php

namespace App\Illuminate\Database;
use File;
use Storage;

abstract class Seeder extends \Illuminate\Database\Seeder
{
    protected $initializations = [];
    function __construct()
    {
        if (Storage::disk('database')->exists('database.json')) {
            $this->initializations = json_decode(Storage::disk('database')->get('database.json'), JSON_UNESCAPED_UNICODE);
        }
    }
}
