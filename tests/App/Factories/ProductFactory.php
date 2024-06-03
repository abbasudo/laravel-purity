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
            'price'        => $this->faker->randomFloat(2),
            'rate'         => $this->faker->randomFloat(2),
            'is_available' => $this->faker->boolean,
        ];
    }
}
