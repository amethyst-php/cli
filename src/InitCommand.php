<?php

namespace Railken\Amethyst\Skeleton;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InitCommand extends Command
{
    protected static $defaultName = 'init';

    protected function configure()
    {
        $this
            ->setDescription('Initialize a new library.')
            ->setHelp('Initialize a new library')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the package')
            ->addOption('dir', 'd', InputOption::VALUE_REQUIRED, 'Target directory', getcwd())
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stubs = new Stubs($output);

        $output->writeln(['<info>Initializing a new package...</info>', '']);

        $stubs->generateNewFiles([
            'package-name' => $input->getArgument('name'),
        ], __DIR__.'/../stubs/package', $input->getOption('dir'));
    }
}
