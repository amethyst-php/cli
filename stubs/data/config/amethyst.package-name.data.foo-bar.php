<?php

return [
    'table'      => 'amethyst_foo_bars',
    'comment'    => 'FooBar',
    'model'      => MyNamespace\Models\FooBar::class,
    'schema'     => MyNamespace\Schemas\FooBarSchema::class,
    'repository' => MyNamespace\Repositories\FooBarRepository::class,
    'serializer' => MyNamespace\Serializers\FooBarSerializer::class,
    'validator'  => MyNamespace\Validators\FooBarValidator::class,
    'authorizer' => MyNamespace\Authorizers\FooBarAuthorizer::class,
    'faker'      => MyNamespace\Fakers\FooBarFaker::class,
    'manager'    => MyNamespace\Managers\FooBarManager::class,
];
