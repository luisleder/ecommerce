<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->text(),
            'image' => $this->faker->imageUrl(format: 'jpg'),
            'brand' => $this->faker->company(),
            'price' => $this->faker->randomFloat(2,100,10000),
            'price_sale' => $this->faker->randomFloat(2,100,10000),
            'category' => $this->faker->jobTitle(),
            'stock' => $this->faker->randomDigitNotNull(),
        ];
    }
}
