<?php

namespace Railken\Amethyst\Tests;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class LibraryInitializeCommandTest extends BaseTest
{
    public function testLibraryInitializeCommand()
    {
        $application = new Application();

        $application->add(new \Railken\Amethyst\Cli\LibraryInitializeCommand());

        $command = $application->find('lib:init');

        $commandTester = new CommandTester($command);
        $commandTester->setInputs([
            'test',
            'author',
            'Author\\Test',
        ]);

        $commandTester->execute([
            'command'  => $command->getName(),
            '--dir'    => $this->getDir(),
        ]);

        $output = $commandTester->getDisplay();

        $this->assertContains('LICENSE', $output);
        $this->assertContains('README.md', $output);
        $this->assertContains('artisan', $output);
        $this->assertContains('composer.json', $output);
        $this->assertContains('phpstan.neon', $output);
        $this->assertContains('phpunit.xml', $output);
        $this->assertContains('.env.example', $output);
        $this->assertContains('.travis.yml', $output);
        $this->assertContains('tests/BaseTest', $output);
        $this->assertContains('src/Providers/TestServiceProvider', $output);
        $this->assertContains('composer.lock', $output);

        $this->assertEquals(true, file_exists($this->getDir().'/.gitignore'));
    }
}
