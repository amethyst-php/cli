<?php

return [
    'table'      => 'amethyst_{{ EntityName|tableize|pluralize }}',
    'comment'    => '{{ EntityName|classify }}',
    'model'      => {{ MyNamespace|classify }}\Models\{{ EntityName|classify }}::class,
    'schema'     => {{ MyNamespace|classify }}\Schemas\{{ EntityName|classify }}Schema::class,
    'repository' => {{ MyNamespace|classify }}\Repositories\{{ EntityName|classify }}Repository::class,
    'serializer' => {{ MyNamespace|classify }}\Serializers\{{ EntityName|classify }}Serializer::class,
    'validator'  => {{ MyNamespace|classify }}\Validators\{{ EntityName|classify }}Validator::class,
    'authorizer' => {{ MyNamespace|classify }}\Authorizers\{{ EntityName|classify }}Authorizer::class,
    'faker'      => {{ MyNamespace|classify }}\Fakers\{{ EntityName|classify }}Faker::class,
    'manager'    => {{ MyNamespace|classify }}\Managers\{{ EntityName|classify }}Manager::class,
];
