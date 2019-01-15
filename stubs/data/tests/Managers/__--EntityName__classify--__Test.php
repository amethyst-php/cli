<?php

namespace {{ MyNamespace|classify }}\Tests\Managers;

use {{ MyNamespace|classify }}\Fakers\{{ EntityName|classify }}Faker;
use {{ MyNamespace|classify }}\Managers\{{ EntityName|classify }}Manager;
use {{ MyNamespace|classify }}\Tests\BaseTest;
use Railken\Lem\Support\Testing\TestableBaseTrait;

class {{ EntityName|classify }}Test extends BaseTest
{
    use TestableBaseTrait;

    /**
     * Manager class.
     *
     * @var string
     */
    protected $manager = {{ EntityName|classify }}Manager::class;

    /**
     * Faker class.
     *
     * @var string
     */
    protected $faker = {{ EntityName|classify }}Faker::class;
}
