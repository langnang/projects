<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ModuleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $globalMetaStatusOption = unserialize(\App\Models\Option::where('name', 'global.status')->where('user', 0)->first('value')->value);
        // var_dump($globalMetaStatusOption);
        //
        foreach (\Module::allEnabled() as $module) {
            \App\Models\Meta::upsert(
                ['name' => "Module:" . $module->getName(), 'slug' => 'module:' . $module->getAlias(), 'type' => 'module', 'status' => 'public'],
                ['slug'],
                ['name', 'type', 'status']
            );
            // $moduleMeta = \App\Models\Meta::where('slug', 'module:' . $module->getAlias())->where('user', 0)->first();
            // foreach ($globalMetaStatusOption as $globalStatus) {
            //     $status = $globalStatus['value'];
            //     \App\Models\Meta::upsert(
            //         ['slug' => 'module:' . $module->getAlias() . ':' . $status, 'type' => 'module-status', 'status' => $status, 'parent' => $moduleMeta->id],
            //         ['slug'],
            //         ['type', 'status']
            //     );
            // }
        }
    }
}
