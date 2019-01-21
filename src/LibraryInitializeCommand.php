<?php

namespace Railken\Amethyst\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class LibraryInitializeCommand extends Command
{
    protected static $defaultName = 'lib:init';

    protected function configure()
    {
        $this
            ->setDescription('Initialize a new library.')
            ->setHelp('Initialize a new library')
            ->addOption('dir', null, InputOption::VALUE_REQUIRED, 'Target directory', getcwd())
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stubs = new Stubs($output);

        $output->writeln(['<info>Initializing a new package...</info>', '']);

        if (!$input->getOption('dir')) {
            return $output->writeln('<error>No directory found</error>');
        }

        $helper = $this->getHelper('question');

        $package = basename(getcwd());
        $question = new Question(sprintf('Package name <comment>[%s]</comment>: ', $package), $package);
        $package = $helper->ask($input, $output, $question);

        $processUser = posix_getpwuid(posix_geteuid());
        $user = $processUser['name'] ?? 'me';
        $question = new Question(sprintf('Author <comment>[%s]</comment>: ', $user), $user);
        $author = $helper->ask($input, $output, $question);

        $namespace = ucfirst($author).'\\Amethyst';
        $question = new Question(sprintf('Namespace <comment>[%s]</comment>: ', $namespace), $namespace);
        $namespace = $helper->ask($input, $output, $question);

        /*$prefix = 'amethyst';
        $question = new Question(sprintf('Prefix <comment>[%s]</comment>: ', $prefix), $prefix);
        $prefix = $helper->ask($input, $output, $question);*/

        $stubs->generateNewFiles([
            'MyNamespace' => $namespace,
            'PackageName' => $package,
            'Author'      => $author,
        ], __DIR__.'/../stubs/package', $input->getOption('dir'));

        file_put_contents($input->getOption('dir').'/.gitignore', implode("\n", [
            'vendor/',
            'var/',
            '\.php_cs\.cache',
            '\.env',
            'composer\.lock',
            'build/',
        ]));
    }
}
