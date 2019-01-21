<?php

namespace Railken\Amethyst\Cli;

use Laravel\Installer\Console\NewCommand as Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class TestStyleCommand extends Command
{
    use Concerns\StartProcess;

    protected function configure()
    {
        $this
            ->setName('test:style')
            ->setDescription('Test style')
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
        $targets = ['app', 'tests', 'src', 'database', 'config'];
        $errors = 0;

        foreach ($targets as $target) {
            if (file_exists($input->getOption('dir').'/'.$target)) {
                $command = sprintf(
                    'php-cs-fixer fix %s --allow-risky="yes" --diff --dry-run --config=%s',
                    $target,
                    __DIR__.'/../resources/.php_cs.dist'
                );

                $errors += $this->startProcess(Process::fromShellCommandline($command, $input->getOption('dir')));
            }
        }

        return $errors;
    }
}
