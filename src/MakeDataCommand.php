<?php

namespace Amethyst\Cli;

use Eloquent\Composer\Configuration\ConfigurationReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class MakeDataCommand extends Command
{
    protected static $defaultName = 'make:data';

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
            ->setDescription('Add a new data')
            ->setHelp('Create migration, config, model, faker, tests files')
            ->addOption('dir', 'd', InputOption::VALUE_REQUIRED, 'Target directory', getcwd())
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stubs = new Stubs($output);

        $output->writeln(['<info>Adding migrations, tests, models, etc...</info>', '']);

        if (!$input->getOption('dir')) {
            return $output->writeln('<error>No directory found</error>');
        }

        $helper = $this->getHelper('question');

        $composerPath = $input->getOption('dir').'/composer.json';
        $question = new Question(sprintf('Composer location <comment>[%s]</comment>: ', $composerPath), $composerPath);
        $composerPath = $helper->ask($input, $output, $question);

        if (!file_exists($composerPath)) {
            return $output->writeln(sprintf('<error>File not found: %s</error>', $composerPath));
        }

        $composer = $this->composerReader->read($composerPath);

        $type = $composer->type();

        $output->writeln(sprintf('<info>Detecting type: %s</info>', $type));

        if (!in_array($type, ['project', 'library'])) {
            $output->writeln(sprintf('<error>Only project or library are allowed, detected: %s</error>', $type));
        }

        $question = new Question(sprintf('Name data (e.g. Book): '), 'Book');
        $data = $helper->ask($input, $output, $question);

        if ($type === 'project') {
            $package = 'app';
            $namespace = 'App';
        }

        if ($type === 'library') {
            $package = $composer->extra()->amethyst->package;
            $namespace = $composer->extra()->amethyst->namespace;
        }

        $stubs->generateNewFiles([
            'MyNamespace' => $namespace,
            'PackageName' => $package,
            'src' => $type === 'project' ? 'app' : 'src',
            'EntityName'  => $data,
        ], __DIR__.'/../stubs/data', $input->getOption('dir'));
    }
}
