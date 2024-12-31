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

        $adminMeta = \App\Models\Meta::where('slug', 'module:admin')->first()->id;
        var_dump($adminMeta);

        \App\Models\Meta::upsert([
        ], ['slug'], ['slug', 'ico', 'name', 'description', 'type', 'status', 'parent']);

    }
}
