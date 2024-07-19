<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call(UsersTableSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(SubscriptionsTableSeeder::class);
        $this->call(PlannedApplicationTypesTableSeeder::class);
        $this->call(OtcsTableSeeder::class);
        $this->call(ContractPeriodsTableSeeder::class);
        $this->call(LocationsTableSeeder::class);
        $this->call(PlannedApplicationsTableSeeder::class);
        $this->call(AccountStatusesTableSeeder::class);
        //$this->call(CustomersTableSeeder::class); 
        $this->call(BillingTypesTableSeeder::class); 
        $this->call(MenusTableSeeder::class); 
        $this->call(BillingStatusesTableSeeder::class); 
        $this->call(SettingsTableSeeder::class);
        $this->call(PaymentMethodsTableSeeder::class);
    }
}
