<?php

namespace MyNamespace\Tests\Managers;

use MyNamespace\Fakers\FooBarFaker;
use MyNamespace\Managers\FooBarManager;
use MyNamespace\Tests\BaseTest;
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
