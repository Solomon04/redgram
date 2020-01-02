<?php

namespace App\Commands;

use App\Services\Instagram\CaptionManager;
use App\Services\Instagram\CredentialsManger;
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
    protected $description = 'Setup Instagram credentials';

    /**
     * Execute the console command.
     *
     * @param CredentialsManger $credentialsManger
     * @param CaptionManager $captionManager
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle(CredentialsManger $credentialsManger, CaptionManager $captionManager)
    {
        $username = $this->ask('What is your username?');
        $password = $this->secret('What is your password?');

        $phrase = $this->ask('What do you want your default caption to be?');
        $hashtags = $this->ask('What do you want your hashtags to be? (No line breaks)');

        return $credentialsManger->save($username, $password) && $captionManager->save($username, $phrase, $hashtags);
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
