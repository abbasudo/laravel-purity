<?php

namespace Abbasudo\Purity\Tests\App\Factories;

use Abbasudo\Purity\Tests\App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name'         => $this->faker->name,
            'description'  => $this->faker->words(5, true),
            'price'        => $this->faker->randomFloat(2, 20, 30),
            'rate'         => $this->faker->randomFloat(2, 20, 30),
            'is_available' => $this->faker->boolean,
        ];
    }
}
