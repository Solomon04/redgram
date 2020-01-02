<?php

namespace App\Commands;

use App\Contracts\Reddit\Scraper;
use App\Exceptions\Filesystem\CredentialsAreMissingException;
use App\Exceptions\Filesystem\InvalidCredentialStructureException;
use App\Exceptions\Filesystem\VerifyDeviceException;
use App\Exceptions\InvalidArrayStructureException;
use App\Services\Instagram\CaptionManager;
use App\Services\Instagram\PostService;
use App\Services\Reddit\ScraperService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use LaravelZero\Framework\Commands\Command;

class PostCommand extends Command
{
    const YES = "Yes";
    const NO = "No";

    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * @var
     */
    private $filesystem;

    public function __construct(Kernel $kernel, Filesystem $filesystem)
    {
        parent::__construct();
        $this->kernel = $kernel;
        $this->filesystem = $filesystem;
    }

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
     * @param PostService $postService
     * @param Scraper $scraperService
     * @param CaptionManager $captionManager
     * @return void
     */
    public function handle(PostService $postService, Scraper $scraperService, CaptionManager $captionManager)
    {
        try{
            $posts = $scraperService->filterPosts();
        }catch (FileNotFoundException $exception){
            $this->kernel->call('setup:reddit');
            return;
        }catch (InvalidArrayStructureException $exception){
            $this->kernel->call('setup:reddit');
            return;
        }

        foreach ($posts as $post){
            try{
                $caption = $captionManager->get();
            }catch (FileNotFoundException $exception){
                $this->kernel->call('setup:instagram');
            }

            $decision = $this->choice('Want to post this?', ['Yes', 'No']);
            if($decision == self::YES){
                $image = file_get_contents($post['image']);
                $path = config('filesystems.path.posted') . DIRECTORY_SEPARATOR . $post['id'] . '.jpg';
                $this->filesystem->put($path, $image);

                try{
                    $postService->login()->post($path, $caption, false);
                }catch (InvalidCredentialStructureException $exception){

                }catch (CredentialsAreMissingException $exception){

                }catch (VerifyDeviceException $exception){

                }
                return;
            }else{
                continue;
            }
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
