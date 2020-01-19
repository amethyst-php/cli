<?php

namespace {{ MyNamespace|classify }}\Tests;

abstract class BaseTest extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh');
    }

    protected function getPackageProviders($app)
    {
        return [
            \{{ MyNamespace|classify }}\Providers\{{ PackageName|classify }}ServiceProvider::class,
        ];
    }
}
