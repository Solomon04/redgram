<?php


namespace Tests\Feature\Services\Instagram;


use App\Contracts\Instagram\Authentication;
use Mockery\Mock;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Services\Instagram\AuthenticationService
 */
class AuthenticationServiceTest extends TestCase
{
    /**
     * @var Authentication | Mock
     */
    private $auth;

    protected function setUp(): void
    {
        parent::setUp();
        $this->auth = $this->mock(Authentication::class);
    }

    /**
     * @covers ::login
     */
    public function test_login_to_instagram()
    {
        $this->auth->shouldReceive('login')
            ->andReturn(null);
        $this->assertNull($this->auth->login());
    }
}
