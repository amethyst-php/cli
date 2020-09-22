<?php

namespace {{ MyNamespace|classify }}\Managers;

use Amethyst\Core\ConfigurableManager;
use Railken\Lem\Manager;

class {{ EntityName|classify }}Manager extends Manager
{
    use ConfigurableManager;

    /**
     * @var string
     */
    protected $config = 'amethyst.{{ PackageName|kebab }}.data.{{ EntityName|kebab }}';
}
