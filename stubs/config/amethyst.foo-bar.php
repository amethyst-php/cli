<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    |
    | Here you can change the table name and the class components.
    |
    */
    'managers' => [
        'foo-bar' => [
            'table' => 'amethyst_foo_bars',
            'comment' => 'foo-bar',
            'model' => Railken\Amethyst\Models\FooBar::class,
            'schema' => Railken\Amethyst\Schemas\FooBarSchema::class,
            'repository' => Railken\Amethyst\Repositories\FooBarRepository::class,
            'serializer' => Railken\Amethyst\Serializers\FooBarSerializer::class,
            'validator' => Railken\Amethyst\Validators\FooBarValidator::class,
            'authorizer' => Railken\Amethyst\Authorizers\FooBarAuthorizer::class,
            'faker' => Railken\Amethyst\Fakers\FooBarFaker::class,
            'manager' => Railken\Amethyst\Managers\FooBarManager::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Http configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the routes
    |
    */
    'http' => [
        'admin' => [
            'foo-bar' => [
                'enabled'     => true,
                'controller'  => Railken\Amethyst\Http\Controllers\Admin\FooBarsController::class,
                'router'      => [
                    'as'        => 'foo-bar.',
                    'prefix'    => '/foo-bars',
                ],
            ],
        ],
    ],
];
