<?php

namespace Railken\Amethyst\Tests;

class CommandTest extends BaseTest
{
    public function testExecution()
    {
        \Railken\Amethyst\Skeleton\Command::main([
            null,
            'new',
            'Test Something',
        ], getcwd().'/var/cache/');

        \Railken\Amethyst\Skeleton\Command::main([
            null,
            'data',
            'Test Something',
            'My Data',
        ], getcwd().'/var/cache/amethyst-test-something');
    }
}
