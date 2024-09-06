<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomDate = randomDate();

        return [
            'date' => $randomDate,
            'description' => $this->faker->sentence,
            'category_id' => Category::inRandomOrder()->first(),
            'user_id' => User::inRandomOrder()->whereNull('customer_id')->first(),
            'amount' => $this->faker->randomFloat(2, 100, 500),
        ];
    }
}
