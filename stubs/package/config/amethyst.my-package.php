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
    'data' => [
        'my-data' => [
            'table'      => 'amethyst_my_datas',
            'comment'    => 'MyData',
            'model'      => Railken\Amethyst\Models\MyData::class,
            'schema'     => Railken\Amethyst\Schemas\MyDataSchema::class,
            'repository' => Railken\Amethyst\Repositories\MyDataRepository::class,
            'serializer' => Railken\Amethyst\Serializers\MyDataSerializer::class,
            'validator'  => Railken\Amethyst\Validators\MyDataValidator::class,
            'authorizer' => Railken\Amethyst\Authorizers\MyDataAuthorizer::class,
            'faker'      => Railken\Amethyst\Authorizers\MyDataFaker::class,
            'manager'    => Railken\Amethyst\Authorizers\MyDataManager::class,
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
            'my-data' => [
                'enabled'     => true,
                'controller'  => Railken\Amethyst\Http\Controllers\Admin\MyDatasController::class,
                'router'      => [
                    'as'        => 'my-data.',
                    'prefix'    => '/my-datas',
                ],
            ],
        ],
    ],
];
