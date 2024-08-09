<?php

namespace App\Console\Commands;

use App\Events\BillGenerated;
use App\Models\Account;
use Illuminate\Console\Command;

class GenerateBill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bill:generate-test {--fiber : Include fiber charge} {--p2p : Include P2P charge}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a bill with optional charges';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fiber = $this->option('fiber');
        $p2p = $this->option('p2p');

        $accounts = Account::allowedBill();

        // avoid conflict scope
        if ($fiber && !$p2p) {
            $accounts->fiber();
        }
        
        // avoid conflict scope
        if ($p2p && !$fiber) {
            $accounts->p2p();
        }

        $accounts = $accounts->get();

        if ($accounts->isNotEmpty()) {
            $this->getOutput()->progressStart($accounts->count());

            foreach ($accounts as $account) {
                event(new BillGenerated($account));
                $this->getOutput()->progressAdvance();
            }

            $this->getOutput()->progressFinish();
        }

        $this->info('Bill generation completed.');
    }

}
