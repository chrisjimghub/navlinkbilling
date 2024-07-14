<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Backpack\Settings\app\Models\Setting;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        
        if (Setting::get('auto_generate_bill') && Setting::get('auto_generate_bill') == "1") {
            dd('enbled_bill');
        }else {
            dd('disabled_bill');
        }

    }
}
