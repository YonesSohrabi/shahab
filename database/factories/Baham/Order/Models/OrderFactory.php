<?php

namespace Database\Factories\Baham\Order\Models;

use Baham\Order\Models\Order;
use Baham\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use function fake;

class OrderFactory extends Factory
{
    protected $model = Order::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'count' => fake()->numberBetween(1, 10),
            'number_remaining' => function (array $order) {
                return $order['count'];
            },
        ];
    }
}
