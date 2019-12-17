<?php


namespace Tests\Feature\Services\Instagram;


use App\Contracts\Instagram\Caption;
use App\Services\Instagram\CaptionManager;
use Illuminate\Contracts\Filesystem\Filesystem;
use Mockery\Mock;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Services\Instagram\CaptionManager
 */
class CaptionManagerTest extends TestCase
{
    /**
     * @var Mock | CaptionManager
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
        $this->manager = $this->mock(Caption::class);
        $this->filesystem = $this->app->make('filesystem.disk');
    }

    /**
     * @covers ::exists
     */
    public function test_caption_file_exists()
    {
        $this->manager->shouldReceive('exists')
            ->andReturn(false);
        $this->assertFalse($this->manager->exists());
    }

    /**
     * @covers ::save
     */
    public function test_save_caption()
    {
        $this->manager->shouldReceive('save')
            ->with('foo', 'bar', '#test')
            ->andReturn(true);
        $saved = $this->manager->save('foo', 'bar', '#test');
        $this->assertTrue($saved);
    }

    /**
     * @covers ::get
     */
    public function test_get_caption()
    {
        $this->filesystem->put(config('filesystems.path.caption'), 'test');
        $this->manager->shouldReceive('get')->andReturn('test');
        $this->assertEquals('test', $this->manager->get());
    }
}
