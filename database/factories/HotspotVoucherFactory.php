<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Status;
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
            // 'payment_method_id' => PaymentMethod::whereNotIn('id', [2,4])->inRandomOrder()->first(),
            // 'status_id' => Status::inRandomOrder()->first(),
        ];
    }

    public function paidBankCheck(): static
    {   
        return $this->state(fn (array $attributes) => [
            'status_id' => 1,
            'payment_method_id' => 4, // bank/check
            'payment_details' => $this->paymentDetails()
        ]);
    }

    public function paymentDetails()
    {
        $data = [];

        $data[] = [
            'check_issued_date' => randomDate(),
            'check_number' => $this->faker->unique()->numerify('##########'), // generates a unique 10-digit number
        ];

        return $data;
    }

    public function paid(): static
    {   
        return $this->state(fn (array $attributes) => [
            'status_id' => 1,
            'payment_method_id' => PaymentMethod::whereNotIn('id', [2,4])->inRandomOrder()->first(),
        ]);
    }

    public function unpaid(): static
    {   
        return $this->state(fn (array $attributes) => [
            'status_id' => 2,
            'user_id' => null,
        ]);
    }
}
