<?php

namespace Railken\Amethyst\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class GitUnstagedCommand extends Command
{
    use Concerns\StartProcess;

    protected function configure()
    {
        $this
            ->setName('git:unstaged')
            ->setDescription('Check status libraries')
            ->addOption('dir', 'd', InputOption::VALUE_REQUIRED, 'Target directory', getcwd())
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = 'git diff-index --quiet HEAD --';

        return $this->startProcess($input, $output, Process::fromShellCommandline($command, $input->getOption('dir')));
    }
}
