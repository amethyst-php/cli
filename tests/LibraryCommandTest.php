<?php

namespace Amethyst\Tests;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Process\Process;

class LibraryCommandTest extends BaseTest
{
    public function testCycle()
    {
        $application = new Application();

        $application->add(new \Amethyst\Cli\MakeDataCommand());
        $application->add(new \Amethyst\Cli\LibraryInitializeCommand());
        $application->add(new \Amethyst\Cli\TestCommand());
        $application->add(new \Amethyst\Cli\TestPhpunitCommand());
        $application->add(new \Amethyst\Cli\TestPhpstanCommand());
        $application->add(new \Amethyst\Cli\TestStyleCommand());

        $command = $application->find('lib:init');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs([
            'amethyst',
            'amethyst-php',
            'test',
            'author',
            'Amethyst\\Test',
        ]);
        $commandTester->execute([
            'command' => $command->getName(),
            '--dir'   => $this->getDir(),
        ]);

        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('LICENSE', $output);
        $this->assertStringContainsString('README.md', $output);
        $this->assertStringContainsString('composer.json', $output);
        $this->assertStringContainsString('phpunit.xml.dist', $output);
        $this->assertStringContainsString('tests/BaseTest', $output);
        $this->assertStringContainsString('src/Providers/TestServiceProvider', $output);
        $this->assertStringContainsString('composer.lock', $output);

        $this->assertEquals(true, file_exists($this->getDir().'/.gitignore'));

        $command = $application->find('make:data');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['', '']);
        $commandTester->execute([
            'command' => $command->getName(),
            '--dir'   => $this->getDir(),
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('tests/Managers/BookTest', $output);
        $this->assertStringContainsString('config/amethyst.test.data.book', $output);
        $this->assertStringContainsString('src/Fakers/BookFaker', $output);
        $this->assertStringContainsString('src/Managers/BookManager', $output);
        $this->assertStringContainsString('src/Authorizers/BookAuthorizer', $output);
        $this->assertStringContainsString('src/Schemas/BookSchema', $output);
        $this->assertStringContainsString('src/Serializers/BookSerializer', $output);
        $this->assertStringContainsString('src/Validators/BookValidator', $output);
        $this->assertStringContainsString('src/Models/Book', $output);
        $this->assertStringContainsString('src/Repositories/BookRepository', $output);
        $this->assertStringContainsString('database/migrations/0000_00_00_000000_create_books_table', $output);

        $vars = [
            'APP_NAME'    => 'Laravel',
            'DB_HOST'     => '127.0.0.1',
            'DB_PORT'     => '3306',
            'DB_DATABASE' => 'laravel',
            'DB_USERNAME' => 'root',
            'DB_PASSWORD' => 'password',
        ];

        // copy($this->getDir().'/.env.example', $this->getDir().'/.env');

        array_walk($vars, function (&$a, $b) {
            $a = "$b='".getenv($b, $a)."'";
        });
        $vars = implode(' ', $vars);

        $process = Process::fromShellCommandline(implode(' && ', [
            'composer install',
            $vars.' ./vendor/bin/phpunit',
        ]), $this->getDir());
        $process->mustRun(null);

        $command = $application->find('test');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            '--dir'   => $this->getDir(),
        ]);
    }
}
