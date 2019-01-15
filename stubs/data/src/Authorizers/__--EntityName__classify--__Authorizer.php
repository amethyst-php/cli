<?php

namespace {{ MyNamespace|classify }}\Authorizers;

use Railken\Lem\Authorizer;
use Railken\Lem\Tokens;

class {{ EntityName|classify }}Authorizer extends Authorizer
{
    /**
     * List of all permissions.
     *
     * @var array
     */
    protected $permissions = [
        Tokens::PERMISSION_CREATE => '{{ EntityName|kebab }}.create',
        Tokens::PERMISSION_UPDATE => '{{ EntityName|kebab }}.update',
        Tokens::PERMISSION_SHOW   => '{{ EntityName|kebab }}.show',
        Tokens::PERMISSION_REMOVE => '{{ EntityName|kebab }}.remove',
    ];
}
