<?php

namespace Railken\Amethyst\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class GitUpdateCommand extends Command
{
    use Concerns\StartProcess;

    protected function configure()
    {
        $this
            ->setName('git:update')
            ->setDescription('Check status libraries')
            ->addOption('dir', 'd', InputOption::VALUE_REQUIRED, 'Target directory', getcwd())
            ->addOption('message', 'm', InputOption::VALUE_REQUIRED, 'Message', 'update')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->startProcess(Process::fromShellCommandline(sprintf('git add -A && git commit -m "%s"', $input->getOption('message')), $input->getOption('dir')));
    }
}
