<?php

namespace Modules\Develop\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DevelopDatabaseSeeder extends Seeder
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
            ['name' => "Module:Develop", 'slug' => 'module:develop', 'type' => 'module', 'status' => 'public'],
            ['slug'],
            ['name', 'type', 'status']
        );
    }
}
