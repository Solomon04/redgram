<?php


namespace Tests\Feature\Services\Filesystem;


use App\Contracts\Filesystem\Credentials;
use App\Services\Filesystem\CredentialsManager;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Crypt;
use Mockery\Mock;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Services\Filesystem\CredentialsManager
 */
class CredentialsManagerTest extends TestCase
{
    /**
     * @var CredentialsManager | Mock
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
        $this->manager = $this->mock(Credentials::class);
        $this->filesystem = $this->app->make('filesystem.disk');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->filesystem->deleteDirectory('redgram-testing');
    }

    /**
     * @covers ::exists
     */
    public function test_credentials_file_exists()
    {
        $this->manager->shouldReceive('exists')
            ->once()
            ->andReturn(true);
        $this->assertTrue($this->manager->exists());
    }

    /**
     * @covers ::save
     */
    public function test_save_credentials()
    {
        $this->manager->shouldReceive('save')
            ->with('foo', 'bar')
            ->andReturn(true);
        $saved = $this->manager->save('foo', 'bar');
        $this->assertTrue($saved);
    }

    /**
     * @covers ::get
     */
    public function test_get_credentials()
    {
        $cryptVal = 'eyJpdiI6IlwvVmRWc2tiMU12VUU5SFFYdWpVNnVRPT0iLCJ2YWx1ZSI6ImIxVjVcL2dhYk5MallmVWZjenBTUE1PSjVqRFwvd1dQVDRwMFFCdmRXM1I1a0xwK0owNE5IQ2N3NFgzRllSQ243QiIsIm1hYyI6ImM3OGI3MTFmYTQ2ZDE1YzA0OTljZDMxMDYzY2E2NjM5MGZmNmUzNDdmYmJkYzA4ZWVjZmNmNTQ0ODZmNGEwMGUifQ==';
        $this->filesystem->put(config('filesystems.path.credentials'), $cryptVal);

        $this->manager->shouldReceive('get')->andReturn(['username' => 'foo', 'password' => 'bar']);
        $credentials = $this->manager->get();
        $this->assertEquals(['username' => 'foo', 'password' => 'bar'], $credentials);
    }
}
