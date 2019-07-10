<?php

namespace Amethyst\Cli;

use GuzzleHttp\Client;
use Laravel\Installer\Console\NewCommand as Command;
use Railken\Dotenv\Dotenv;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Process\Process;
use ZipArchive;

class ProjectNewCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('project:new')
            ->setDescription('Create a new project.')
            ->addArgument('name', InputArgument::OPTIONAL)
            ->addOption('dev', null, InputOption::VALUE_NONE, 'Installs the latest "development" release')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Forces install even if the directory already exists')
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
        if (!class_exists('ZipArchive')) {
            throw new RuntimeException('The Zip PHP extension is not installed. Please install it and try again.');
        }

        $directory = ($input->getArgument('name')) ? getcwd().'/'.$input->getArgument('name') : getcwd();

        if (!$input->getOption('force')) {
            $this->verifyApplicationDoesntExist($directory);
        }

        $output->writeln('<info>Crafting application...</info>');

        $version = $this->getVersion($input);

        $this->download($zipFile = $this->makeFilename(), $version)
             ->extract($zipFile, $directory)
             ->prepareWritableDirectories($directory, $output)
             ->cleanUp($zipFile);

        $composer = $this->findComposer();

        $this->prepareEnv($input, $output, $directory);

        $commands = [
            $composer.' install --no-scripts',
            $composer.' run-script post-root-package-install',
            $composer.' run-script post-create-project-cmd',
            $composer.' run-script post-autoload-dump',
        ];

        if ($input->getOption('no-ansi')) {
            $commands = array_map(function ($value) {
                return $value.' --no-ansi';
            }, $commands);
        }

        if ($input->getOption('quiet')) {
            $commands = array_map(function ($value) {
                return $value.' --quiet';
            }, $commands);
        }

        $process = new Process(implode(' && ', $commands), $directory, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $process->run(function ($type, $line) use ($output) {
            $output->write($line);
        });

        $output->writeln('<comment>Application ready! Build something amazing.</comment>');
    }

    /**
     * Download the temporary Zip to the given file.
     *
     * @param string $zipFile
     * @param string $version
     *
     * @return $this
     */
    protected function download($zipFile, $version = 'master')
    {
        $response = (new Client())->get('https://github.com/railken/starter-amethyst/archive/'.$version.'.zip');
        file_put_contents($zipFile, $response->getBody());

        return $this;
    }

    /**
     * Generate a random temporary dirname.
     *
     * @return string
     */
    protected function makeDirname()
    {
        return getcwd().'/laravel_'.md5(time().uniqid());
    }

    /**
     * Extract the Zip file into the given directory.
     *
     * @param string $zipFile
     * @param string $directory
     *
     * @return $this
     */
    protected function extract($zipFile, $directory)
    {
        $tmpDirectory = $this->makeDirname();

        $archive = new ZipArchive();
        $archive->open($zipFile);
        $archive->extractTo($tmpDirectory);
        $archive->close();

        $extraction = glob($tmpDirectory.'/*')[0];

        rename($extraction, $directory);
        rmdir($tmpDirectory);

        return $this;
    }

    /**
     * Prepare env file.
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string                                            $directory
     */
    protected function prepareEnv(InputInterface $input, OutputInterface $output, string $directory)
    {
        $helper = $this->getHelper('question');

        $vars = [
            'APP_NAME'    => 'Laravel',
            'DB_HOST'     => '127.0.0.1',
            'DB_PORT'     => '3306',
            'DB_DATABASE' => 'homestead',
            'DB_USERNAME' => 'homestead',
            'DB_PASSWORD' => 'secret',
        ];

        copy($directory.DIRECTORY_SEPARATOR.'.env.example', $directory.DIRECTORY_SEPARATOR.'.env');

        $dotenv = new Dotenv($directory);
        $dotenv->overload();

        foreach ($vars as $key => $default) {
            $question = new Question(sprintf('%s <comment>[%s]</comment>: ', $key, $default), $default);
            $value = $helper->ask($input, $output, $question);

            $dotenv->updateVariable($key, $value);
        }
    }
}
