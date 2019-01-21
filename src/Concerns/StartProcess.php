<?php


namespace Railken\Amethyst\Cli\Concerns;

use Symfony\Component\Process\Process;

trait StartProcess
{
    public function startProcess(Process $process)
    {
        $process->setTty(Process::isTtySupported())->start();

        foreach ($process as $type => $data) {
            echo $data;
        }

        return $process->getExitCode();
    }
}