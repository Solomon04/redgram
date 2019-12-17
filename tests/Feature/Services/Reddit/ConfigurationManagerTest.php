<?php


namespace Tests\Feature\Services\Reddit;


use App\Contracts\Reddit\Configuration;
use App\Services\Reddit\ConfigurationManager;
use Illuminate\Contracts\Filesystem\Filesystem;
use Mockery\Mock;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Services\Reddit\ConfigurationManager
 */
class ConfigurationManagerTest extends TestCase
{
    /**
     * @var Mock | ConfigurationManager
     */
    private $manager;

    /**
     * @var Filesystem
     */
    private $filesystem;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config->set('filesystems.disks.local.root', base_path() . DIRECTORY_SEPARATOR . 'redgram-testing');
        $this->manager = $this->mock(Configuration::class);
        $this->filesystem = $this->app->make('filesystem.disk');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @covers ::exists
     */
    public function test_reddit_file_exists()
    {
        $this->manager->shouldReceive('exists')
            ->once()
            ->andReturn(false);
        $this->assertFalse($this->manager->exists());
    }

    /**
     * @covers ::save
     */
    public function test_save_reddit_file()
    {
        $this->manager->shouldReceive('save')
            ->with('foo', 'new', 10, false)
            ->andReturn(true);
        $saved = $this->manager->save('foo', 'new', 10, false);
        $this->assertTrue($saved);
    }

    /**
     * @covers ::get
     */
    public function test_get_reddit_file()
    {
        $data = ['subreddit' => 'foo', 'sort' => 'new', 'minScore' => 1, 'allowVideos' => false];
        $this->filesystem->put(config('filesystems.path.reddit'), json_encode($data));
        $this->manager->shouldReceive('get')->andReturn($data);
        $this->assertEquals($data, $this->manager->get());
    }
}
