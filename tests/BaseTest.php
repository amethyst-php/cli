<?php

namespace Railken\Amethyst\Tests;

abstract class BaseTest extends \PHPUnit\Framework\TestCase
{
    public function setUp()
    {
        $dotenv = \Dotenv\Dotenv::create(__DIR__.'/..', '.env');
        $dotenv->load();

        parent::setUp();

        $dir = __DIR__.'/../var';

        if (file_exists($dir)) {
            $di = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS);
            $ri = new \RecursiveIteratorIterator($di, \RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($ri as $file) {
                $file->isDir() ? rmdir($file) : unlink($file);
            }
            rmdir($dir);
        }

        mkdir($dir, 0777, true);
    }

    public function getDir()
    {
        return __DIR__.'/../var/cache';
    }
}
