<?php

namespace Amethyst\Cli;

use Laravel\Installer\Console\NewCommand as Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class TestPhpstanCommand extends Command
{
    use Concerns\StartProcess;

    protected function configure()
    {
        $this
            ->setName('test:phpstan')
            ->setDescription('Test phpstan')
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
        $targets = ['app', 'src'];
        $errors = 0;

        foreach ($targets as $target) {
            if (file_exists($input->getOption('dir').'/'.$target)) {
                $command = sprintf(
                    'phpstan analyze %s --level=max -c %s',
                    $target,
                     __DIR__.'/../resources/phpstan.neon'
                );

                $errors += $this->startProcess($input, $output, Process::fromShellCommandline($command, $input->getOption('dir')));
            }
        }

        return $errors;
    }
}
