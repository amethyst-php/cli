<?php

namespace MyNamespace\Http\Controllers\Admin;

use MyNamespace\Api\Http\Controllers\RestManagerController;
use MyNamespace\Api\Http\Controllers\Traits as RestTraits;
use MyNamespace\Managers\FooBarManager;

class FooBarsController extends RestManagerController
{
    use RestTraits\RestIndexTrait;
    use RestTraits\RestShowTrait;
    use RestTraits\RestCreateTrait;
    use RestTraits\RestUpdateTrait;
    use RestTraits\RestRemoveTrait;

    /**
     * The class of the manager.
     *
     * @var string
     */
    public $class = FooBarManager::class;
}
