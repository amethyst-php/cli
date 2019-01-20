<?php

namespace Railken\Amethyst\Cli;

use Laravel\Installer\Console\NewCommand as Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

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
        $targets = ['app', 'tests', 'src'];

        foreach ($targets as $target) {
            if (file_exists($input->getOption('dir').'/'.$target)) {
                $command = sprintf(
                    'phpstan analyze %s --level=max -c %s',
                    $target,
                     __DIR__.'/../resources/phpstan.neon'
                );

                $output->writeln(sprintf('<info>phpstan fix %s</info>', $target));


                $process = Process::fromShellCommandline($command, $input->getOption('dir'));
                $this->startProcess($process);
            }
        }

        $targets = ['app', 'tests', 'src', 'database', 'config'];

        foreach ($targets as $target) {
            if (file_exists($input->getOption('dir').'/'.$target)) {
                $command = sprintf(
                    'php-cs-fixer fix %s --allow-risky="yes" --config=%s',
                    $target,
                    __DIR__.'/../resources/.php_cs.dist'
                );
                $output->writeln(sprintf('<info>php-cs-fixer fix %s</info>', $target));
                $process = Process::fromShellCommandline($command, $input->getOption('dir'));
                $this->startProcess($process);
            }
        }

        $command = './vendor/bin/phpunit --coverage-html=./build/reports/phpunit --coverage-clover=build/logs/clover.xml --verbose --debug';
        $output->writeln('<info>phpunit</info>');
        $command = sprintf($command);
        $process = Process::fromShellCommandline($command, $input->getOption('dir'));
        $this->startProcess($process);
    }

    public function startProcess($process)
    {
        $process->setTty(Process::isTtySupported())->start();

        foreach ($process as $type => $data) {
            echo $data;
        }
    }
}
