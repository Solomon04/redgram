<?php


namespace Tests\Feature\Services\Instagram;


use App\Contracts\Instagram\Post;
use InstagramAPI\Response\ConfigureResponse;
use Mockery\Mock;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Services\Instagram\PostService
 */
class PostServiceTest extends TestCase
{
    /**
     * @var Post | Mock
     */
    private $post;

    protected function setUp(): void
    {
        parent::setUp();
        $this->post = $this->mock(Post::class);
    }

    /**
     * @covers ::login
     */
    public function test_login_to_instagram()
    {
        $this->post->shouldReceive('login')
            ->andReturn($this->post);
        $this->assertEquals($this->post, $this->post->login());
    }

    /**
     * @covers ::post
     */
    public function test_post_image_to_instagram()
    {
        $this->post->shouldReceive('post')
            ->andReturn(new ConfigureResponse());
        $this->assertInstanceOf(ConfigureResponse::class, $this->post->post('', '', false));
    }

}
