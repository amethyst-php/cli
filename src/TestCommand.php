<?php

namespace Amethyst\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('test')
            ->setDescription('Test')
            ->addOption('dir', null, InputOption::VALUE_REQUIRED, 'Target directory', getcwd())
        ;
    }

    /**
     * Execute the command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('test:phpunit');

        $command->run(new ArrayInput([
            '--dir'     => $input->getOption('dir'),
            '--verbose' => $input->getOption('verbose'),
        ]), $output);

        $command = $this->getApplication()->find('test:phpstan');

        $command->run(new ArrayInput([
            '--dir'     => $input->getOption('dir'),
            '--verbose' => $input->getOption('verbose'),
        ]), $output);

        $command = $this->getApplication()->find('test:style');

        $command->run(new ArrayInput([
            '--dir'     => $input->getOption('dir'),
            '--verbose' => $input->getOption('verbose'),
        ]), $output);

        return 0;
    }
}
