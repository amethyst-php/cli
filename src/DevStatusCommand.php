<?php

namespace Amethyst\Cli;

use Eloquent\Composer\Configuration\ConfigurationReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class DevStatusCommand extends Command
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

    public function testTravis(string $packageName)
    {
        $content = file_get_contents(sprintf('https://api.travis-ci.org/%s.svg', $packageName));

        return strpos($content, 'pass') ? 0 : 1;
    }

    public function testPhpunit(string $dir)
    {
        $command = $this->getApplication()->find('test:phpunit');

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

    public function testPhpstan(string $dir)
    {
        $command = $this->getApplication()->find('test:phpstan');

        return  intval($command->run(new ArrayInput([
            '--dir' => $dir,
        ]), new \Symfony\Component\Console\Output\BufferedOutput()));
    }

    public function testStyle(string $dir)
    {
        $command = $this->getApplication()->find('test:style');

        return  intval($command->run(new ArrayInput([
            '--dir' => $dir,
        ]), new \Symfony\Component\Console\Output\BufferedOutput()));
    }

    protected function configure()
    {
        $this
            ->setName('dev:status')
            ->setDescription('Check status libraries')
            ->addOption('dir', 'd', InputOption::VALUE_REQUIRED, 'Target directory', getcwd())
            ->addOption('force', 'f', InputOption::VALUE_OPTIONAL, 'Force', false)
        ;
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

                $output->writeln(['------------']);
                $output->writeln([sprintf('Package: <info>%s</info>', $composer->name())]);
                $output->writeln(['']);

                $errors += $travisCode = $this->testTravis($composer->name());
                $errors += $phpunitCode = $this->testPhpunit($dir);
                $errors += $phpstanCode = $this->testPhpstan($dir);
                $errors += $gitCode = $this->testGit($dir);
                $errors += $styleCode = $this->testStyle($dir);

                $output->writeln([sprintf('Phpunit: %s', $phpunitCode === 0 ? '<info>Ok</info>' : '<error>Error</error>')]);
                $output->writeln([sprintf('Phpstan: %s', $phpstanCode === 0 ? '<info>Ok</info>' : '<error>Error</error>')]);
                $output->writeln([sprintf('Travis: %s', $travisCode === 0 ? '<info>Ok</info>' : '<error>Error</error>')]);
                $output->writeln([sprintf('Git: %s', $gitCode === 0 ? '<info>Ok</info>' : '<error>Detected changes</error>')]);
                $output->writeln([sprintf('Style: %s', $styleCode === 0 ? '<info>Ok</info>' : '<error>Error</error>')]);
                $output->writeln([''], ['']);

                if ($input->getOption('force') === false && $errors !== 0) {
                    $question = new ConfirmationQuestion('Shall we continue?');

                    if (!$helper->ask($input, $output, $question)) {
                        break;
                    }
                }
            }
        }
    }
}
