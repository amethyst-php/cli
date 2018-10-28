<?php

namespace Railken\Amethyst\Tests;

class CommandTest extends BaseTest
{
    public function testExecution()
    {
        \Railken\Amethyst\Skeleton\Command::main([
            null,
            'new',
            'My Package',
        ], getcwd().'/var/cache/');

        \Railken\Amethyst\Skeleton\Command::main([
            null,
            'data',
            'My Package',
            'My Data',
        ], getcwd().'/var/cache/my-package');
    }
}
