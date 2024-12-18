<?php

namespace Modules\WebHunt\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class WebHuntDatabaseSeeder extends Seeder
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
            ['name' => "Module:WebHunt", 'slug' => 'module:webhunt', 'type' => 'module', 'status' => 'public'],
            ['slug'],
            ['name', 'type', 'status']
        );
    }
}
