<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Account;
use App\Models\Category;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HotspotVoucher>
 */
class HotspotVoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id' => Account::factory()->hotspotVoucher()->connected()->withPivotData()->create(),
            'date' => randomDate(),
            'user_id' => User::inRandomOrder()->whereNull('customer_id')->first(),
            'category_id' => Category::inRandomOrder()->first(),
            'amount' => $this->faker->randomFloat(2, 500, 1200),
            'payment_method_id' => PaymentMethod::where('id', '!=', 2)->inRandomOrder()->first()->id,
        ];
    }
}
