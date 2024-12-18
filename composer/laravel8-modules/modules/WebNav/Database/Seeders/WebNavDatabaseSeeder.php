<?php

namespace Modules\WebNav\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class WebNavDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
        \DB::table('metas')->upsert(
            ['name' => "Module:WebNav", 'slug' => 'module:webnav', 'type' => 'module', 'status' => 'public'],
            ['slug'],
            ['name', 'type', 'status']
        );

        // $moduleMetaId = \App\Models\Meta::where('slug', 'module:webnav')->value('mid');

        // $metas = array_map(function ($item) use ($moduleMetaId) {
        //     return array_merge($item, ["type" => 'category', "parent" => $moduleMetaId]);
        // }, \App\Models\Meta::factory(10)->make()->toArray());
        // \DB::table('metas')->upsert(
        //     $metas,
        //     ['slug'],
        //     ['name', 'type', 'status']
        // );
        // $metas = array_map(function ($item) use ($moduleMetaId) {
        //     return array_merge($item, [
        //         "type" => 'category',
        //         "parent" => \App\Models\Meta::where('parent', $moduleMetaId)->where('type', 'category')->inRandomOrder()->value("mid")
        //     ]);
        // }, \App\Models\Meta::factory(10)->make()->toArray());
        // \DB::table('metas')->upsert(
        //     $metas,
        //     ['slug'],
        //     ['name', 'type', 'status']
        // );
    }
}
