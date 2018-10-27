<?php

namespace Railken\Amethyst\Tests\Http\Admin;

use Illuminate\Support\Facades\Foo;
use Railken\Amethyst\Api\Support\Testing\TestableBaseTrait;
use Railken\Amethyst\Fakers\FooBarFaker;
use Railken\Amethyst\Tests\BaseTest;

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
     * Base path config.
     *
     * @var string
     */
    protected $config = 'admin.foo-bar';
}
