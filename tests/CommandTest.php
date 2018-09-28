<?php

namespace Railken\Amethyst\Tests;

class CommandTest extends BaseTest
{
    public function testExecution()
    {
        \Railken\Amethyst\Skeleton\Command::main([
            null,
            'test',
            'Test',
        ]);
    }
}
