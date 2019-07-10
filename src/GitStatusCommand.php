<?php

namespace Amethyst\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class GitStatusCommand extends Command
{
    use Concerns\StartProcess;

    protected function configure()
    {
        $this
            ->setName('git:status')
            ->setDescription('Check status libraries')
            ->addOption('dir', 'd', InputOption::VALUE_REQUIRED, 'Target directory', getcwd())
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->startProcess($input, $output, Process::fromShellCommandline('git status', $input->getOption('dir')));

        $command = $this->getApplication()->find('git:unstaged');

        return intval($command->run(new ArrayInput([
            '--dir' => $input->getOption('dir'),
        ]), $output));
    }
}
