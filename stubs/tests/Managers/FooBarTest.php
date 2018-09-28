<?php

namespace Railken\Amethyst\Tests\Managers;

use Railken\Amethyst\Fakers\FooBarFaker;
use Railken\Amethyst\Managers\FooBarManager;
use Railken\Amethyst\Tests\BaseTest;
use Railken\Lem\Support\Testing\TestableBaseTrait;

class FooBarTest extends BaseTest
{
    use TestableBaseTrait;

    /**
     * Manager class.
     *
     * @var string
     */
    protected $manager = FooBarManager::class;

    /**
     * Faker class.
     *
     * @var string
     */
    protected $faker = FooBarFaker::class;
}
