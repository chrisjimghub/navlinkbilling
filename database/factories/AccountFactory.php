<?php

namespace Database\Factories;

use App\Models\Otc;
use App\Models\Account;
use App\Models\BillingGrouping;
use App\Models\Customer;
use App\Models\Subscription;
use App\Models\ContractPeriod;
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
            'customer_id' => Customer::factory(),
            'subscription_id' => Subscription::inRandomOrder()->first()->id,
            'planned_application_id' => PlannedApplication::inRandomOrder()->first()->id,
            'installed_date' => today(),
            'installed_address' => $this->faker->address(),
            'google_map_coordinates' => $this->faker->latitude() . ', ' . $this->faker->longitude(),
            'notes' => $this->faker->sentence(),
            'account_status_id' => $this->faker->randomElement([1, 2, 3, 4]),
            'billing_grouping_id' => BillingGrouping::inRandomOrder()->first(),
        ];
    }

    public function fiber(): static
    {   
        return $this->state(fn (array $attributes) => [
            'subscription_id' => 2,
            'billing_grouping_id' => BillingGrouping::inRandomOrder()->first(),
        ]);
    }

    public function p2p(): static
    {   
        return $this->state(fn (array $attributes) => [
            'subscription_id' => 1,
            'billing_grouping_id' => BillingGrouping::inRandomOrder()->first(),
        ]);
    }

    public function pisoWifi(): static
    {   
        return $this->state(fn (array $attributes) => [
            'subscription_id' => 3,
            'billing_grouping_id' => null,
        ]);
    }

    public function hotspotVoucher(): static
    {   
        return $this->state(fn (array $attributes) => [
            'subscription_id' => 4,
            'billing_grouping_id' => null,
        ]);
    }

    public function connected(): static
    {   
        return $this->state(fn (array $attributes) => [
            'account_status_id' => 1,
        ]);
    }

    public function disconnected(): static
    {   
        return $this->state(fn (array $attributes) => [
            'account_status_id' => 3,
        ]);
    }

    public function installing(): static
    {   
        return $this->state(fn (array $attributes) => [
            'account_status_id' => 3,
        ]);
    }

    public function noBilling(): static
    {   
        return $this->state(fn (array $attributes) => [
            'account_status_id' => 4,
        ]);
    }

    /**
     * Indicate that the account has related OTCs and ContractPeriods.
     *
     * @return $this
     */
    public function withPivotData(): self
    {
        return $this->afterCreating(function (Account $account) {
            // Attach OTCs to the account (pivot table `account_otc`)
            $otcs = Otc::inRandomOrder()->take(rand(1, 2))->get();
            $account->otcs()->attach($otcs);

            // Attach ContractPeriods to the account (pivot table `account_contract_period`)
            $contractPeriods = ContractPeriod::inRandomOrder()->take(rand(1,2))->get(); 
            $account->contractPeriods()->attach($contractPeriods);
        });
    }
}
