<?php

namespace Amethyst\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class DevGitCommand extends Command
{
    /**
     * Create a new instance of the command.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function testGit(string $dir)
    {
        $command = $this->getApplication()->find('git:status');

        return  intval($command->run(new ArrayInput([
            '--dir' => $dir,
        ]), new \Symfony\Component\Console\Output\BufferedOutput()));
    }

    protected function configure()
    {
        $this
            ->setName('dev:git')
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

        foreach (glob($input->getOption('dir').'/*') as $dir) {
            if (is_dir($dir)) {
                $errors = 0;

                $output->writeln(['------------']);
                $output->writeln([sprintf('Package: <info>%s</info>', basename($dir))]);
                $output->writeln(['']);

                $errors += $gitCode = $this->testGit($dir);

                $output->writeln([sprintf('Git: %s', $gitCode === 0 ? '<info>Ok</info>' : '<error>Detected changes</error>')]);
                $output->writeln([''], ['']);

                if ($errors !== 0) {
                    $question = new ConfirmationQuestion('Shall we continue?');

                    if (!$helper->ask($input, $output, $question)) {
                        break;
                    }
                }
            }
        }
    }
}
