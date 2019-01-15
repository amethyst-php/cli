<?php

namespace Railken\Amethyst\Tests;

abstract class BaseTest extends \PHPUnit\Framework\TestCase
{
	public function setUp()
	{
        $dir = __DIR__.'/../var';

        $di = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS);
		$ri = new \RecursiveIteratorIterator($di, \RecursiveIteratorIterator::CHILD_FIRST);
		foreach ( $ri as $file ) {
		    $file->isDir() ?  rmdir($file) : unlink($file);
		}

	}
}
