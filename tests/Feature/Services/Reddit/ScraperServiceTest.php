<?php


namespace Tests\Feature\Services\Reddit;


use App\Contracts\Reddit\Scraper;
use App\Services\Reddit\ScraperService;
use Illuminate\Contracts\Filesystem\Filesystem;
use Tests\TestCase;
/**
 * @coversDefaultClass \App\Services\Reddit\ScraperService
 */
class ScraperServiceTest extends TestCase
{
    /**
     * @var ScraperService
     */
    private $scraper;

    /**
     * @var Filesystem
     */
    private $filesystem;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config->set('filesystems.disks.local.root', base_path() . DIRECTORY_SEPARATOR . 'redgram-testing');
        $this->scraper = $this->app->make(Scraper::class);
        $this->filesystem = $this->app->make('filesystem.disk');
    }

    public function test_get_json_data()
    {
        $this->assertIsObject($this->scraper->getJsonData('ProgrammerHumor', 'hot'));
    }

    public function test_filter_posts()
    {
        $data = ['subreddit' => 'ProgrammerHumour', 'sort' => 'new', 'minScore' => 20, 'allowVideos' => false];
        $this->filesystem->put(config('filesystems.path.reddit'), json_encode($data));
        $this->assertIsArray($this->scraper->filterPosts());
    }
}
