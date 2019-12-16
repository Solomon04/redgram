<?php

namespace App\Commands;

use App\Services\Reddit\ScraperService;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class PostingCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'post';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Post content from Reddit to Instagram.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(ScraperService $scraperService)
    {
        $scraperService->getPosts();
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
