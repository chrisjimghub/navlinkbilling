<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Billing;
use App\Models\Expense;
use App\Events\BillProcessed;
use App\Models\PaymentMethod;
use Illuminate\Support\Carbon;
use App\Models\BillingGrouping;
use Illuminate\Console\Command;
use App\Models\PlannedApplication;
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

            $amounts = [
                1 => 100,
                2 => 200,
                3 => 300,
                4 => 400,
                5 => 500,
            ];

            // bill with 1 month advance payment
            $billings = Billing::inRandomOrder()->limit($this->option('advancePayment') ?? 3)->get();
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
            $billings = Billing::inRandomOrder()->limit($this->option('advancePayment2Month') ?? 2)->get();
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
            $billings = Billing::inRandomOrder()->limit($this->option('serviceInterruption') ?? 5)->get();
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
            $billings = Billing::inRandomOrder()->limit($this->option('changePlan') ?? 2)->get();
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
            $billings = Billing::inRandomOrder()->limit($this->option('excessWire') ?? 5)->get();
            foreach ($billings as $billing) {
                $particulars = $billing->particulars; 
                
                $particulars[] = [
                    'description' => 'Excess Wire',
                    'amount' => $amounts[rand(1, 5)],
                ];
                $billing->particulars = $particulars; 
                $billing->save(); 
            }            

            // bill with Lorem Ipsum
            $billings = Billing::inRandomOrder()->limit($this->option('loremIpsum') ?? 3)->get();
            foreach ($billings as $billing) {
                $particulars = $billing->particulars; 
                
                $particulars[] = [
                    'description' => 'Lorem Ipsum',
                    'amount' => $amounts[rand(1, 5)] - rand(50, 300),
                ];
                $billing->particulars = $particulars; 
                $billing->save(); 
            }

            // bill installment type
            $accounts = Account::connected()->inRandomOrder()->limit($this->option('installmentType') ?? 10)->get();
            foreach ($accounts as $account) {
                Billing::create([
                    'account_id' => $account->id,
                    'billing_type_id' => 1, //installment
                ]);
            }

            // paid bill
            $billings = Billing::inRandomOrder()->limit($this->option('paid') ?? 10)->get();
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
            Expense::factory($this->option('sales') ?? 50)->create();

            // TODO:: wifi harvest factory
            // TODO:: wifi voucher factory
        }
    }
}
