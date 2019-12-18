<?php

namespace App\Commands;

use App\Exceptions\Filesystem\CredentialsAreMissingException;
use App\Exceptions\Filesystem\InvalidCredentialStructureException;
use App\Exceptions\Filesystem\VerifyDeviceException;
use App\Services\Instagram\AuthenticationService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Console\Kernel;
use LaravelZero\Framework\Commands\Command;

class SetupCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'setup';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Setup and save your credentials for Redgram.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(AuthenticationService $service, Kernel $kernel)
    {
        try{
            $service->login();
        }catch (InvalidCredentialStructureException $exception){
            $this->alert($exception->getMessage());
            sleep(2);
            $kernel->call('setup:instagram');

        }catch (CredentialsAreMissingException $exception){
            $this->alert($exception->getMessage());
            sleep(2);
            $kernel->call('setup:instagram');
        }catch (VerifyDeviceException $exception){
            $this->error($exception->getMessage());
        }

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
