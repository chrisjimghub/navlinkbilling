<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\App;

class UpdateNavLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'navlink:update {--no-composer : Do not run composer update}';

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
        // if (!$this->option('no-composer')) {
        //     $this->info('Running composer update...');
        //     $composerCommand = ['composer', 'update'];
        //     if (App::environment('production')) {
        //         $composerCommand[] = '--no-dev';
        //     }
        //     $this->runProcess($composerCommand);

        // } else {
        //     $this->info('Skipping composer due to --no-composer flag.');
        // }

        

        $this->info('Stashing git changes...');
        $this->runProcess(['git', 'stash']);

        $this->info('Resetting git changes...');
        $this->runProcess(['git', 'checkout', '.']);

        $this->info('Cleaning untracked files...');
        $this->runProcess(['git', 'clean', '-fd']);

        $this->info('Pulling latest changes from origin master...');
        $this->runProcess(['git', 'pull', 'origin', 'master']);

        if (App::environment('local')) {
            $this->info('Refreshing the database...');
            Artisan::call('migrate:fresh', ['--seed' => true]);
            $this->info(Artisan::output());
        } elseif (App::environment('production')) {
            $this->info('Running database migrations...');
            Artisan::call('migrate', ['--force' => true]);
            $this->info(Artisan::output());
        } else {
            $this->info('Skipping database operations since environment is neither local nor production.');
        }

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
