<?php

namespace {{ MyNamespace|classify }}\Tests\Http\Admin;

use Amethyst\Core\Support\Testing\TestableBaseTrait;
use {{ MyNamespace|classify }}\Fakers\{{ EntityName|classify }}Faker;
use {{ MyNamespace|classify }}\Tests\BaseTest;

class {{ EntityName|classify }}Test extends BaseTest
{
    use TestableBaseTrait;

    /**
     * Faker class.
     *
     * @var string
     */
    protected $faker = {{ EntityName|classify }}Faker::class;

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
    protected $route = 'admin.{{ EntityName|kebab }}';
}
