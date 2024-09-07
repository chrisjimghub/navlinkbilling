<?php

namespace App\Console\Commands;

use App\Models\Sales;
use App\Models\Account;
use App\Models\Billing;
use App\Models\Expense;
use App\Events\BillProcessed;
use App\Models\PaymentMethod;
use App\Models\HotspotVoucher;
use Illuminate\Support\Carbon;
use App\Models\BillingGrouping;
use Illuminate\Console\Command;
use App\Models\PlannedApplication;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Facades\DB;
use App\Models\AccountServiceInterruption;
use App\Http\Controllers\Admin\Traits\GenerateBill;
use App\Http\Controllers\Admin\Traits\BillingPeriod;
use App\Http\Controllers\Admin\Traits\AdvancePayment;

class TestData extends Command
{
    use BillingPeriod;
    use GenerateBill;
    use AdvancePayment;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:data 
        {--fiber= : Number of Fiber bill (int)} 
        {--p2p= : Number of P2P bill (int)} 
        {--advancePayment= : Number of bill with advance payment in particulars (int)}
        {--advancePayment2Month= : Number of bill with 2 months advance payment in particulars (int)}
        {--serviceInterruption= : Number of bill with service interruptions in particulars (int)}
        {--changePlan= : Number of bill with change plan or upgrade/downgrade (int)}
        {--excessWire= : Number of bill with exceess wire in particulars (int)}
        {--loremIpsum= : Number of bill with lorem ipsum in particulars (int)}
        {--installmentType= : Number of bill with type installment (int)} 
        {--paid= : Number of paid bill (int)}
        {--expenses= : Number of expenses factory (int)}
        {--sales= : Number of sales factory (int)}
        {--wifiHarvest= : Number of wifi harvest factory (int)}
        {--hotspotVoucher= : Number of hotspot voucher factory (int)}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command generate factory data for testing.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (config('app.env') == 'local') {
            Account::factory($this->option('fiber') ?? 10)->fiber()->connected()->withPivotData()->create();
            Account::factory($this->option('p2p') ?? 10)->p2p()->connected()->withPivotData()->create();

            $this->generateBill(BillingGrouping::all());

            $faker = FakerFactory::create();

            // bill with 1 month advance payment
            $billings = Billing::billingCrud()->whereHas('account', function ($query) {
                $query->connected();
            })->inRandomOrder()->limit($this->option('advancePayment') ?? 3)->get();
            
            foreach ($billings as $billing) {
                $month = Carbon::parse($billing->date_end)->addMonth()->format('F');

                $particulars = $billing->particulars; 
                $particulars[] = [
                    'description' => 'Advance Payment ('.$month.')',
                    'amount' => $billing->monthly_rate,
                ];

                $billing->particulars = $particulars; 
                $billing->save(); 
            }

            // bill with 2 months advance payment
            $billings = Billing::billingCrud()->whereHas('account', function ($query) {
                $query->connected();
            })->inRandomOrder()->limit($this->option('advancePayment2Month') ?? 2)->get();

            foreach ($billings as $billing) {
                $month = Carbon::parse($billing->date_end)->addMonth()->format('F');
                $month2 = Carbon::parse($billing->date_end)->addMonths(2)->format('F');

                $particulars = $billing->particulars; 
                
                $particulars[] = [
                    'description' => 'Advance Payment ('.$month.')',
                    'amount' => $billing->monthly_rate,
                ];

                $particulars[] = [
                    'description' => 'Advance Payment ('.$month2.')',
                    'amount' => $billing->monthly_rate,
                ];

                $billing->particulars = $particulars; 
                $billing->save(); 
            }

            // bill with interruptions
            $billings = Billing::billingCrud()->whereHas('account', function ($query) {
                $query->connected();
            })->inRandomOrder()->limit($this->option('serviceInterruption') ?? 5)->get();

            foreach ($billings as $billing) {
                $subDays1 = rand(1, 10);
                $subDays2 = rand(1, 10);

                // Ensure $subDays1 is always greater than $subDays2
                if ($subDays1 <= $subDays2) {
                    $subDays1 = $subDays2 + 1;
                }

                AccountServiceInterruption::create([
                    'account_id' => $billing->account_id,
                    'date_start' => Carbon::parse($billing->date_end)->subDays($subDays1),
                    'date_end' => Carbon::parse($billing->date_end)->subDays($subDays2),
                ]);
            }

            // bill with upgrade/downgrade
            $billings = Billing::billingCrud()->whereHas('account', function ($query) {
                $query->connected();
            })->inRandomOrder()->limit($this->option('changePlan') ?? 2)->get();

            foreach ($billings as $billing) {
                $dateChange = Carbon::parse($billing->date_end)->subDays(rand(8, 15));
                $plannedApp = PlannedApplication::inRandomOrder()->first();

                $beforeAccountSnapshot = [];
                $beforeAccountSnapshot = $billing->before_account_snapshot ?? $billing->account_snapshot;

                $beforeAccountSnapshot['date_change'] = $dateChange;
                $billing->before_account_snapshot = $beforeAccountSnapshot;
                $billing->saveQuietly(); 
                
                $billing->account()->update(['planned_application_id' => $plannedApp->id]);
                event(new BillProcessed($billing));
            }

            // bill with Excess Wire
            $billings = Billing::billingCrud()->whereHas('account', function ($query) {
                $query->connected();
            })->inRandomOrder()->limit($this->option('excessWire') ?? 5)->get();

            foreach ($billings as $billing) {
                $particulars = $billing->particulars; 
                
                $particulars[] = [
                    'description' => 'Excess Wire',
                    'amount' => $faker->randomFloat(2, 100, 500),
                ];
                $billing->particulars = $particulars; 
                $billing->save(); 
            }            

            // bill with Lorem Ipsum
            $billings = Billing::billingCrud()->whereHas('account', function ($query) {
                $query->connected();
            })->inRandomOrder()->limit($this->option('loremIpsum') ?? 3)->get();

            foreach ($billings as $billing) {
                $particulars = $billing->particulars; 
                
                $particulars[] = [
                    'description' => 'Lorem Ipsum',
                    'amount' => $faker->randomFloat(2, 100, 700),
                ];
                $billing->particulars = $particulars; 
                $billing->save(); 
            }

            // bill installment type
            $accounts = Account::billingCrud()->connected()->inRandomOrder()->limit($this->option('installmentType') ?? 10)->get();
            foreach ($accounts as $account) {
                Billing::create([
                    'account_id' => $account->id,
                    'billing_type_id' => 1, //installment
                ]);
            }

            // paid bill
            $billings = Billing::billingCrud()->whereHas('account', function ($query) {
                $query->connected();
            })->inRandomOrder()->limit($this->option('paid') ?? 10)->get();

            foreach ($billings as $billing) {
                try {
                    DB::beginTransaction();
                    $billing->payment_method_id = PaymentMethod::where('id', '!=', 2)->inRandomOrder()->first()->id;
                    $billing->markAsPaid();
                    $billing->saveQuietly();  
        
                    $this->advancePayment($billing);
        
                    DB::commit();
        
                } catch (\Exception $e) {
                    DB::rollback();
                    throw $e; 
                } 
            }

            // factories
            Expense::factory($this->option('expenses') ?? 50)->create();
            Sales::factory($this->option('sales') ?? 50)->create();
            HotspotVoucher::factory($this->option('hotspotVoucher') ?? 50)->create();                                                                                                                                                                                                   

            // wifi harvest factory
            $accounts = Account::factory($this->option('wifiHarvest') ?? 10)->pisoWifi()->connected()->withPivotData()->create();
            foreach ($accounts as $account) {
                $billing = Billing::create([
                    'account_id' => $account->id,
                    'billing_type_id' => 3, // Harvest Piso Wifi 
                    'date_start' => randomDate(),
                ]);

                $particulars = $billing->particulars ?? [];
                
                foreach ($particulars as &$item) {
                    if (str_contains(strtolower($item['description']), 'revenue ')) {

                        $item['amount'] = $faker->randomFloat(2, 1500, 5000);

                    }elseif (str_contains(strtolower($item['description']), 'electric bill')) {
                        
                        $item['amount'] = -$faker->randomFloat(2, 400, 500);

                    }elseif (str_contains(strtolower($item['description']), 'lessor')) {
                        
                        $item['amount'] = -$faker->randomFloat(2, 350, 700);

                    }

                }
                unset($item); // Break the reference to avoid unexpected behavior

                $billing->billing_status_id = rand(4,5); // 4=harvested, 5=unharvested
                $billing->particulars = $particulars;
                $billing->saveQuietly();
            }
        }
    }
}
