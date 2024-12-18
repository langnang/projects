<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AdminDatabaseSeeder extends Seeder
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
            ['name' => "Module:Admin", 'slug' => 'module:admin', 'type' => 'module', 'status' => 'public'],
            ['slug'],
            ['name', 'type', 'status']
        );
    }
}
