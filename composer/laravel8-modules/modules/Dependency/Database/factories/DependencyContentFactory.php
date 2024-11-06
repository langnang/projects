<?php
namespace Modules\Dependency\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DependencyContentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Dependency\Models\DependencyContent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //

            "slug" => $this->faker->slug(),

            "name" => $this->faker->sentence(),
            "ico" => $this->faker->imageUrl(72, 72),
            "description" => $this->faker->sentence(),
            "text" => '<!--markdown-->\r\n' . $this->faker->text() . '<!--more-->\r\n',
            "type" => $this->faker->randomElement(['draft-post', 'post', 'draft-page', 'page', 'draft-template', 'template', 'draft-collection', 'collection']),
            "status" => $this->faker->randomElement(['publish', 'protect', 'private']),

            "template" => $this->faker->randomNumber(),
            "count" => $this->faker->randomNumber(),
            "order" => $this->faker->randomNumber(),
            "parent" => $this->faker->randomNumber(),
            "user" => $this->faker->randomNumber(),

            "created_at" => $this->faker->date() . ' ' . $this->faker->time(),
            "updated_at" => $this->faker->date() . ' ' . $this->faker->time(),
            "release_at" => $this->faker->date() . ' ' . $this->faker->time(),
        ];
    }
}

