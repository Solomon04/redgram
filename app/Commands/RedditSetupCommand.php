<?php

namespace App\Commands;

use App\Services\Filesystem\ConfigurationManager;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class RedditSetupCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'setup:reddit';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Setup Reddit Information';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(ConfigurationManager $manager)
    {
        $subreddit = $this->ask('What is the name of the subreddit?', 'ProgrammerHumor');

        $sort = $this->choice('Do you want the newest posts or hottest posts?', ['new', 'hot']);

        $minScore = $this->ask('What is the minimum score for a Reddit post?', 25);

        //$this->choice('Do you want to allow videos?')

        $manager->save($subreddit, $sort, $minScore, false);
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
