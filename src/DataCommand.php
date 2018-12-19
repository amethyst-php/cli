<?php

namespace Railken\Amethyst\Skeleton;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DataCommand extends Command
{
    protected static $defaultName = 'data';

    protected function configure()
    {
        $this
            ->setDescription('Add a new  a new library.')
            ->setHelp('Initialize a new library')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the package')
            ->addArgument('data', InputArgument::REQUIRED, 'The data')
            ->addOption('dir', 'd', InputOption::VALUE_REQUIRED, 'Target directory', getcwd())
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stubs = new Stubs($output);

        $output->writeln(['<info>Adding migrations, tests, models, etc...</info>', '']);

        $stubs->generateNewFiles([
            'package-name' => $input->getArgument('name'),
            'foo-bar'      => $input->getArgument('data'),
        ], __DIR__.'/../stubs/data', $input->getOption('dir'));
    }
}
