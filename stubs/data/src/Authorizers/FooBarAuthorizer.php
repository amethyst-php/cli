<?php

namespace MyNamespace\Authorizers;

use Railken\Lem\Authorizer;
use Railken\Lem\Tokens;

class FooBarAuthorizer extends Authorizer
{
    /**
     * List of all permissions.
     *
     * @var array
     */
    protected $permissions = [
        Tokens::PERMISSION_CREATE => 'foo-bar.create',
        Tokens::PERMISSION_UPDATE => 'foo-bar.update',
        Tokens::PERMISSION_SHOW   => 'foo-bar.show',
        Tokens::PERMISSION_REMOVE => 'foo-bar.remove',
    ];
}
