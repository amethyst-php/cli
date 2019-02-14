<?php

namespace Railken\Amethyst\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class QuartzLibraryDataCommand extends Command
{
    protected static $defaultName = 'quartz:lib:data';

    /**
     * Create a new instance of the command.
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a new data')
            ->setHelp('Create data files')
            ->addOption('dir', 'd', InputOption::VALUE_REQUIRED, 'Target directory', getcwd())
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stubs = new Stubs($output);

        $output->writeln(['<info>Adding files...</info>', '']);

        if (!$input->getOption('dir')) {
            return $output->writeln('<error>No directory found</error>');
        }

        $helper = $this->getHelper('question');

        $packagePath = $input->getOption('dir').'/package.json';
        $question = new Question(sprintf('Package location <comment>[%s]</comment>: ', $packagePath), $packagePath);
        $packagePath = $helper->ask($input, $output, $question);

        if (!file_exists($packagePath)) {
            return $output->writeln(sprintf('<error>File not found: %s</error>', $packagePath));
        }

        $package = json_decode(file_get_contents($packagePath));

        $question = new Question(sprintf('Name data (e.g. Book): '), 'Book');
        $data = $helper->ask($input, $output, $question);

        $stubs->generateNewFiles([
            'EntityName'  => $data,
        ], __DIR__.'/../stubs/quartz-data', $input->getOption('dir'));
    }
}
