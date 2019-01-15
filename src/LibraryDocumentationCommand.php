<?php

namespace Railken\Amethyst\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Railken\Amethyst\Cli\Generator\DocumentGenerator;

class LibraryDocumentationCommand extends Command
{
    protected static $defaultName = 'lib:doc';

    protected function configure()
    {
        $this
            ->setDescription('Generate the documentation for the library.')
            ->addOption('dir', null, InputOption::VALUE_REQUIRED, 'Target directory', getcwd())
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $generator = new DocumentGenerator();
        $generator->generateAll(__DIR__.'/../../stubs/doc/library', $input->getOption('dir') ? $input->getOption('dir') : getcwd().'/docs');
    }
}
