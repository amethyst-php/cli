<?php 

return [
    'enabled'     => true,
    'controller'  => MyNamespace\Http\Controllers\Admin\FooBarsController::class,
    'router'      => [
        'as'        => 'foo-bar.',
        'prefix'    => '/foo-bars',
    ],
];