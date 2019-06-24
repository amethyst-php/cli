<?php

namespace Railken\Amethyst\Cli\Concerns;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

trait StartProcess
{
    public function startProcess(InputInterface $input, OutputInterface $output, Process $process)
    {
        $process->setTty(Process::isTtySupported());
        $process->setTimeout(3600);
        
        if ($input->getOption('verbose') === false) {
            $process->disableOutput();
            $process->run();
        } else {
            $process->start();
            foreach ($process as $type => $data) {
                echo $data;
            }
        }

        return $process->getExitCode();
    }
}
