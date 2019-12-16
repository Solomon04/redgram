<?php

namespace App\Commands;

use App\Services\Filesystem\CredentialsManager;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class InstagramSetupCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'setup:instagram';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Setup Instagram information';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(CredentialsManager $manager)
    {
        $username = $this->ask('What is your username?');
        $password = $this->secret('What is your password?');

        return $manager->save($username, $password);
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
