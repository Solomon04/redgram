<?php

namespace Tests;

use Illuminate\Contracts\Config\Repository;
use LaravelZero\Framework\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @var Repository
     */
    protected $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = $this->app->make('config');
    }
}
