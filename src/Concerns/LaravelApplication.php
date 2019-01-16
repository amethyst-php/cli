<?php

namespace Railken\Amethyst\Cli\Concerns;

use Orchestra\Testbench\Concerns\CreatesApplication;

trait LaravelApplication
{
	public function newLaravelApplication()
	{
	 	require __DIR__.'/../bootstrap/app.php';
     	$app->make(Kernel::class)->bootstrap();
 	}
}