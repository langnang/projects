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

    public function getInitializaions($key = null)
    {
        if (empty($key))
            return $this->initializations;

        $return = [];
        $initialization_keys = array_filter(array_keys($this->initializations), function ($item) use ($key) {
            return str_starts_with($item, $key);
        });
        // // var_dump($link_initializations);
        foreach ($initialization_keys as $initialization_key) {
            $mergeData = parse_ini_string(str_replace("&", "\n", \Str::between($initialization_key, '[', ']')));

            $return = array_merge(
                $return,
                array_map(function ($item) use ($mergeData) {
                    return array_merge($item, $mergeData);
                }, array_filter(\Arr::get($this->initializations, $initialization_key, []), function ($item) {
                    return $item['title'];
                }))
            );

            // \App\Models\link::upsert(, ['slug'], ['slug', 'title', 'url', 'ico', 'description', 'keywords', 'type', 'status']);

        }
        return $return;
    }
}
