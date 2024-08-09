<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Console\Command;

class GenerateBillCommand extends Command
{
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
        $accounts = Account::allowedBill()->get();

        dd($accounts->toArray());
    }
}
