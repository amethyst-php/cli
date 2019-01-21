<?php

namespace Railken\Amethyst\Cli;

use Eloquent\Composer\Configuration\ConfigurationReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class DevStatusCommand extends Command
{
    protected static $defaultName = 'dev:status';

    /**
     * @var \Eloquent\Composer\Configuration\ConfigurationReader
     */
    protected $composerReader;

    /**
     * Create a new instance of the command.
     */
    public function __construct()
    {
        $this->composerReader = new ConfigurationReader();

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Check status libraries')
            ->addOption('dir', 'd', InputOption::VALUE_REQUIRED, 'Target directory', getcwd())
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stubs = new Stubs($output);

        if (!$input->getOption('dir')) {
            return $output->writeln('<error>No directory found</error>');
        }

        $helper = $this->getHelper('question');

        foreach(glob($input->getOption('dir')."/*") as $dir) {

            $composerPath = $dir.'/composer.json';

            if (is_dir($dir) && file_exists($composerPath)) {
                $composer = $this->composerReader->read($composerPath);

                $output->writeln([sprintf("Found package: <info>%s</info>", $composer->name())]);


                $content = file_get_contents(sprintf('https://api.travis-ci.org/%s.svg', $composer->name()));
                
                $travisStatus = strpos($content, "pass") ? "<info>Ok</info>" : "<error>Error</error>";

                $output->writeln([sprintf("Travis Status: %s", $travisStatus)]);
                $output->writeln(['']);
            }
        }
    }
}
