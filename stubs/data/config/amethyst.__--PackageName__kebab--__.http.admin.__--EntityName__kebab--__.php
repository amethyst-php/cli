<?php

return [
    'enabled'    => true,
    'controller' => {{ MyNamespace|classify }}\Http\Controllers\Admin\{{ EntityName|pluralize|classify }}Controller::class,
    'router'     => [
        'as'     => '{{ EntityName|kebab }}.',
        'prefix' => '/{{ EntityName|pluralize|kebab }}',
    ],
];
