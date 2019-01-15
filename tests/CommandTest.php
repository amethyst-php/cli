<?php

namespace Railken\Amethyst\Tests;

use Symfony\Component\Console\Application;

class CommandTest extends BaseTest
{
	public function testCycle()
	{

		$application = new Application();

		$application->add(new \Railken\Amethyst\Cli\ProjectNewCommand());
		$application->add(new \Railken\Amethyst\Cli\LibraryInitializeCommand());
		$application->add(new \Railken\Amethyst\Cli\LibraryDataCommand());
		$application->add(new \Railken\Amethyst\Cli\LibraryDocumentationCommand());
		$application->run();
		
	}
}
