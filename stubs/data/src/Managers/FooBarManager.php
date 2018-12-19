<?php

namespace MyNamespace\Managers;

use MyNamespace\Common\ConfigurableManager;
use Railken\Lem\Manager;

class FooBarManager extends Manager
{
    use ConfigurableManager;

    /**
     * @var string
     */
    protected $config = 'amethyst.package-name.data.foo-bar';
}
