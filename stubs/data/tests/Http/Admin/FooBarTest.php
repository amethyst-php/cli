<?php

namespace MyNamespace\Tests\Http\Admin;

use Illuminate\Support\Facades\Foo;
use MyNamespace\Api\Support\Testing\TestableBaseTrait;
use MyNamespace\Fakers\FooBarFaker;
use MyNamespace\Tests\BaseTest;

class FooBarTest extends BaseTest
{
    use TestableBaseTrait;

    /**
     * Faker class.
     *
     * @var string
     */
    protected $faker = FooBarFaker::class;

    /**
     * Router group resource.
     *
     * @var string
     */
    protected $group = 'admin';

    /**
     * Route name.
     *
     * @var string
     */
    protected $route = 'admin.foo-bar';
}
