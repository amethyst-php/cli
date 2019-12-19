<?php

namespace {{ MyNamespace|classify }}\Http\Controllers\Admin;

use Amethyst\Api\Http\Controllers\RestManagerController;
use Amethyst\Api\Http\Controllers\Traits as RestTraits;
use {{ MyNamespace|classify }}\Managers\{{ EntityName|classify }}Manager;

class {{ EntityName|pluralize|classify }}Controller extends RestManagerController
{
    use RestTraits\RestCommonTrait;

    /**
     * The class of the manager.
     *
     * @var string
     */
    public $class = {{ EntityName|classify }}Manager::class;
}
