<?php

namespace Railken\Amethyst\Tests;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Process\Process;

class LibraryCommandTest extends BaseTest
{
    public function testCycle()
    {
        $application = new Application();

        $application->add(new \Railken\Amethyst\Cli\LibraryDataCommand());
        $application->add(new \Railken\Amethyst\Cli\LibraryInitializeCommand());
        $application->add(new \Railken\Amethyst\Cli\LibraryDocumentationCommand());

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

        $command = $application->find('lib:data');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['', '']);
        $commandTester->execute([
            'command'  => $command->getName(),
            '--dir'    => $this->getDir(),
        ]);

        $output = $commandTester->getDisplay();
        $this->assertContains('tests/Http/Admin/BookTest', $output);
        $this->assertContains('config/amethyst.test.data.book', $output);
        $this->assertContains('config/amethyst.test.http.admin.book', $output);
        $this->assertContains('src/Fakers/BookFaker', $output);
        $this->assertContains('src/Managers/BookManager', $output);
        $this->assertContains('src/Authorizers/BookAuthorizer', $output);
        $this->assertContains('src/Schemas/BookSchema', $output);
        $this->assertContains('src/Serializers/BookSerializer', $output);
        $this->assertContains('src/Validators/BookValidator', $output);
        $this->assertContains('src/Models/Book', $output);
        $this->assertContains('src/Repositories/BookRepository', $output);
        $this->assertContains('src/Http/Controllers/Admin/BooksController', $output);
        $this->assertContains('database/migrations/0000_00_00_000000_create_books_table', $output);

        $vars = [
            'APP_NAME'    => 'Laravel',
            'DB_HOST'     => '127.0.0.1',
            'DB_PORT'     => '3306',
            'DB_DATABASE' => 'homestead',
            'DB_USERNAME' => 'homestead',
            'DB_PASSWORD' => 'secret',
        ];

        copy($this->getDir().'/.env.example', $this->getDir().'/.env');

        array_walk($vars, function (&$a, $b) {
            $a = "$b='".getenv($b, $a)."'";
        });
        $vars = implode(' ', $vars);

        $process = Process::fromShellCommandline(implode(' && ', [
            'composer install',
            $vars.' ./vendor/bin/phpunit',
        ]), $this->getDir());
        $process->mustRun(null);


        $command = $application->find('lib:doc');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['']);
        $commandTester->execute([
            'command'  => $command->getName(),
            '--dir'    => $this->getDir(),
        ]);
    }
}
