<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            //
            // 'photo' => $this->faker->imageUrl(),
            'last_name' => $this->faker->lastName,
            'first_name' => $this->faker->firstName,
            'date_of_birth' => $this->faker->date,
            'contact_number' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'block_street' => $this->faker->streetAddress,
            'barangay' => $this->faker->citySuffix,
            'city_or_municipality' => $this->faker->city,
            'social_media' => $this->faker->userName,
            'signature' => $this->faker->imageUrl(),
        ];
    }
}
