<?php


namespace Tests\Feature\Commands;


use Tests\TestCase;

/**
 * @coversDefaultClass \App\Commands\InstagramSetupCommand
 */
class InstagramSetupCommandTest extends TestCase
{
    /**
     * @covers ::handle
     */
    public function test_instagram_setup_command()
    {
        $this->artisan('setup:instagram')
            ->expectsQuestion('What is your username?', 'foo')
            ->expectsQuestion('What is your password?', 'bar')
            ->expectsQuestion('What do you want your default caption to be?', 'foobar')
            ->expectsQuestion('What do you want your hashtags to be? (No line breaks)', '#foo');
    }
}
