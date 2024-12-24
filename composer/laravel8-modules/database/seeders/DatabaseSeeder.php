<?php

namespace Database\Seeders;

use App\Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            OptionTableSeeder::class,
        ]);
        $link_initializations = array_filter(array_keys($this->initializations), function ($key) {
            return str_starts_with($key, 'links');
        });
        // var_dump($link_initializations);
        foreach ($link_initializations as $link_key) {
            $values = parse_ini_string(str_replace("&", "\n", \Str::between($link_key, '[', ']')));

            \App\Models\link::upsert(array_map(function ($item) use ($values) {
                return array_merge($item, $values);
            }, array_filter(\Arr::get($this->initializations, $link_key, []), function ($item) {
                return $item['title'];
            })), ['slug'], ['slug', 'title', 'url', 'ico', 'description', 'keywords', 'type', 'status']);

        }
        // \App\Models\User::factory(1)->insert();
        \App\Models\Meta::factory(100)->create();
        \App\Models\Content::factory(100)->create();
        // \App\Models\Field::factory(1000)->create();
        // \App\Models\Comment::factory(100)->create();
        // \App\Models\Link::factory(10)->create();
        \App\Models\Relationship::factory(100)->create();

        // $this->call("OthersTableSeeder");

        $this->call(array_merge([
            ModuleDatabaseSeeder::class,
        ], array_map(function ($moduleName) {
            return "\Modules\\" . $moduleName . "\Database\Seeders\\" . $moduleName . "DatabaseSeeder";
        }, array_keys(\Module::all()))));

    }
}
