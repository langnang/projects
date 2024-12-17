<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RelationshipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "content_id" => \App\Models\Content::inRandomOrder()->first("cid"),
            "meta_id" => \App\Models\Meta::inRandomOrder()->first("mid"),
            "link_id" => \App\Models\Link::inRandomOrder()->first("lid"),
        ];
    }
}
