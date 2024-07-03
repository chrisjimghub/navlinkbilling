<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Subscription;
use App\Models\PlannedApplication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'customer_id' => Customer::inRandomOrder()->first()->id ?? Customer::factory(),
            'planned_application_id' => PlannedApplication::inRandomOrder()->first()->id ?? PlannedApplication::factory(),
            'subscription_id' => Subscription::inRandomOrder()->first()->id ?? Subscription::factory(),
            'installed_date' => $this->faker->date(),
            'installed_address' => $this->faker->address(),
            'google_map_coordinates' => $this->faker->latitude() . ', ' . $this->faker->longitude(),
            // 'notes' => $this->faker->paragraph(),
            'account_status_id' => $this->faker->randomElement([1, 2, 3]),
        ];
    }
}
