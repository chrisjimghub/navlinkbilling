<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Artisan;

class UpdateNavLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'navlink:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update NavLink billing system and refresh the environment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $this->info('Running composer update...');
        $this->runProcess(['composer', 'update']);

        $this->info('Stashing git changes...');
        $this->runProcess(['git', 'stash']);

        $this->info('Resetting git changes...');
        $this->runProcess(['git', 'checkout', '.']);

        $this->info('Cleaning untracked files...');
        $this->runProcess(['git', 'clean', '-fd']);

        $this->info('Refreshing the database...');
        Artisan::call('migrate:fresh', ['--seed' => true]);
        $this->info(Artisan::output());

        $this->info('Clearing cache...');
        Artisan::call('optimize:clear');
        $this->info(Artisan::output());

        $this->info('All tasks have been completed successfully.');
    }

     // Run an external process and handle errors.
    private function runProcess($command)
    {
        $process = new Process($command);
        $process->setTimeout(null);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        if (!$process->isSuccessful()) {
            $this->error('Command failed: ' . implode(' ', $command));
            exit(1);
        }
    }
}
