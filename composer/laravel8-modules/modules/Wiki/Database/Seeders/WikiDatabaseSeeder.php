<?php

namespace Modules\Wiki\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class WikiDatabaseSeeder extends Seeder
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
        \App\Models\Meta::upsert(
            ['name' => "Module:Wiki", 'slug' => 'module:wiki', 'type' => 'module', 'status' => 'public'],
            ['slug'],
            ['name', 'type', 'status']
        );

        $moduleMetaId = \App\Models\Meta::where('slug', 'module:wiki')->value('id');

        \App\Models\Meta::upsert(
            ['name' => "Module:Wiki", 'slug' => 'module:wiki', 'type' => 'module', 'status' => 'public'],
            ['slug'],
            ['name', 'type', 'status']
        );
    }
}
