<?php

namespace Amethyst\Cli;

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
            $output->writeln('<error>No directory found</error>');

            return 1;
        }

        $helper = $this->getHelper('question');

        $question = new Question(sprintf('Organization name <comment>[%s]</comment>: ', 'amethyst'), 'amethyst');
        $organization = $helper->ask($input, $output, $question);

        $question = new Question(sprintf('Organization slug <comment>[%s]</comment>: ', 'amethyst-php'), 'amethyst-php');
        $organizationSlug = $helper->ask($input, $output, $question);

        $package = basename(getcwd());
        $question = new Question(sprintf('Package name <comment>[%s]</comment>: ', $package), $package);
        $package = $helper->ask($input, $output, $question);

        $processUser = posix_getpwuid(posix_geteuid());
        $user = $processUser['name'] ?? 'me';
        $question = new Question(sprintf('Author <comment>[%s]</comment>: ', $user), $user);
        $author = $helper->ask($input, $output, $question);

        $namespace = ucfirst($organization);
        $question = new Question(sprintf('Namespace <comment>[%s]</comment>: ', $namespace), $namespace);
        $namespace = $helper->ask($input, $output, $question);

        /*$prefix = 'amethyst';
        $question = new Question(sprintf('Prefix <comment>[%s]</comment>: ', $prefix), $prefix);
        $prefix = $helper->ask($input, $output, $question);*/

        $stubs->generateNewFiles([
            'Organization'     => $organization,
            'OrganizationSlug' => $organizationSlug,
            'MyNamespace'      => $namespace,
            'PackageName'      => $package,
            'Author'           => $author,
        ], __DIR__.'/../stubs/package', $input->getOption('dir'));

        file_put_contents($input->getOption('dir').'/.gitignore', implode("\n", [
            'vendor/',
            'var/',
            'phpunit.xml',
            '\.env',
            'composer\.lock',
            'build/',
        ]));

        return 0;
    }
}
