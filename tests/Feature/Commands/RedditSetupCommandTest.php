<?php


namespace Tests\Feature\Commands;


use Tests\TestCase;

/**
 * @coversDefaultClass \App\Commands\RedditSetupCommand
 */
class RedditSetupCommandTest extends TestCase
{
    /**
     * @covers ::handle
     */
    public function test_reddit_setup_command()
    {
        $this->artisan('setup:reddit')
            ->expectsQuestion('What is the name of the subreddit?', 'foo')
            ->expectsQuestion('Do you want the newest posts or hottest posts?', 1)
            ->expectsQuestion('What is the minimum score for a Reddit post?', 10);
    }
}
