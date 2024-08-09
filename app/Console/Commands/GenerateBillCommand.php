<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Traits\GenerateBill;
use App\Models\BillingGrouping;
use Illuminate\Console\Command;

class GenerateBillCommand extends Command
{
    use GenerateBill;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bill:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate bills by groupings';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to generate bills...');
        $group = BillingGrouping::all();
        $this->generateBill($group);
        $this->info('Bill generation completed.');
    }
}
