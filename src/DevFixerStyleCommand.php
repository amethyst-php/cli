<?php

namespace Railken\Amethyst\Cli;

use Eloquent\Composer\Configuration\ConfigurationReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class DevFixerStyleCommand extends Command
{
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
            ->setName('dev:fix:style')
            ->setDescription('Check status libraries')
            ->addOption('dir', 'd', InputOption::VALUE_REQUIRED, 'Target directory', getcwd())
        ;
    }


    public function testTravis(string $packageName)
    {
        $content = file_get_contents(sprintf('https://api.travis-ci.org/%s.svg', $packageName));

        return strpos($content, 'pass') ? 0 : 1;
    }

    public function testStyle(string $dir)
    {
        $command = $this->getApplication()->find('test:style');

        return  intval($command->run(new ArrayInput([
            '--dir' => $dir,
        ]), new \Symfony\Component\Console\Output\BufferedOutput()));
    }

    public function testGit(string $dir)
    {
        $command = $this->getApplication()->find('git:status');

        return  intval($command->run(new ArrayInput([
            '--dir' => $dir,
        ]), new \Symfony\Component\Console\Output\BufferedOutput()));
    }
    public function runFixStyle(string $dir)
    {
        $command = $this->getApplication()->find('fix:style');

        $command->run(new ArrayInput([
            '--dir' => $dir,
        ]), new \Symfony\Component\Console\Output\BufferedOutput());
    }

    public function runGitUpdate(string $dir)
    {
        $command = $this->getApplication()->find('git:update');

        $command->run(new ArrayInput([
            '--dir' => $dir,
            '--message' => 'fix style',
        ]), new \Symfony\Component\Console\Output\BufferedOutput());
    }
    

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stubs = new Stubs($output);

        if (!$input->getOption('dir')) {
            return $output->writeln('<error>No directory found</error>');
        }

        $helper = $this->getHelper('question');

        foreach (glob($input->getOption('dir').'/*') as $dir) {
            $composerPath = $dir.'/composer.json';

            if (is_dir($dir) && file_exists($composerPath)) {
                $errors = 0;

                $composer = $this->composerReader->read($composerPath);

                $errors += $gitCode = $this->testGit($dir);

                $output->writeln(['------------']);
                $output->writeln([sprintf('Report package: <info>%s</info>', $composer->name())]);
                $output->writeln(['']);

                if (($testDiff = $this->testStyle($dir)) !== 0 && $errors === 0) {

                    $output->writeln("<info>Applying automatic fix</info>");
                    $this->runFixStyle($dir);
                    $this->runGitUpdate($dir);
                } else {
                    $output->writeln("<info>Skipped fix style</info>");
                    $output->writeln(sprintf("Staged files: %s", $errors === 0 ? "<info>No problem</info>": "<error>Found changes</error>"));
                    $output->writeln(sprintf("Diff: %s", $testDiff === 0 ? "<info>Fixes no needed</info>": "<error>This package need a fix</error>"));
                }

            }
               
        }
    }
}
