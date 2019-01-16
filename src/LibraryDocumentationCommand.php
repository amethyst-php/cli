<?php

namespace Railken\Amethyst\Cli;

use Eloquent\Composer\Configuration\ConfigurationReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Process\Process;

class LibraryDocumentationCommand extends Command
{
    use Concerns\Export;

    protected static $defaultName = 'lib:doc';

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

    public function getEnvironmentSetUp($app)
    {
        return;
    }

    protected function configure()
    {
        $this
            ->setDescription('Generate the documentation for the library.')
            ->addOption('dir', null, InputOption::VALUE_REQUIRED, 'Target directory', getcwd())
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stubs = new Stubs($output);

        $helper = $this->getHelper('question');

        $composerPath = $input->getOption('dir').'/composer.json';
        $question = new Question(sprintf('Composer location <comment>[%s]</comment>: ', $composerPath), $composerPath);
        $composerPath = $helper->ask($input, $output, $question);

        if (!file_exists($composerPath)) {
            return $output->writeln(sprintf('<error>File not found: %s</error>', $composerPath));
        }

        $composer = $this->composerReader->read($composerPath);

        $packageName = $composer->extra()->amethyst->package;

        copy(__DIR__.'/../bin/bridge', $input->getOption('dir').'/.amethyst-bridge');

        $loadInput = base64_encode(serialize((object) [
            'providers'   => $composer->extra()->laravel->providers,
            'packageName' => $packageName,
        ]));

        try {
            $process = Process::fromShellCommandline('php .amethyst-bridge '.$loadInput, $input->getOption('dir'));
            $process->mustRun();
            $entities = unserialize(base64_decode($process->getOutput()));

            unlink($input->getOption('dir').'/.amethyst-bridge');
        } catch (\Exception $e) {
            unlink($input->getOption('dir').'/.amethyst-bridge');
            throw $e;
        }

        $stubs->generateNewFiles([
            'data'                => $entities,
            'composerPackageName' => $composer->name(),
        ], __DIR__.'/../stubs/docs/library', $input->getOption('dir').'/docs');

        foreach ($entities as $entity) {
            $entity['parameters_formatted'] = $this->var_export54($entity['parameters']);
            $stubs->generateNewFiles([
                'data' => $entity,
            ], __DIR__.'/../stubs/docs/entity', $input->getOption('dir').'/docs/data/'.$entity['name']);
        }
    }
}
