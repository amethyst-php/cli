<?php

namespace Railken\Amethyst\Cli;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Railken\Amethyst\Cli\Generator\DocumentGenerator;
use Railken\Lem\Tokens;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class LibraryDocumentationCommand extends Command
{
    use Concerns\Export;
    protected static $defaultName = 'lib:doc';

    /**
     * Generate the documentation.
     *
     * @param string $stubs
     * @param string $destination
     */
    public function generateAll(string $stubs, string $destination)
    {
        // Use config to retrieve all datas

        foreach (Config::get('amethyst') as $namePackage => $package) {
            foreach ((array) Arr::get($package, 'data') as $nameData => $data) {
                if (Arr::get($data, 'manager')) {
                    $this->addData($namePackage, $nameData, $data);
                }
            }
        }

        $this->generateFile($stubs.'/index.md', $destination.'/index.md', [
            'data' => $this->data,
        ]);
        $this->generateFile($stubs.'/installation.md', $destination.'/installation.md');

        foreach ($this->data as $data) {
            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($stubs.'/entity/')) as $filename) {
                if (is_file($filename)) {
                    $filename = basename($filename);

                    $this->generateFile($stubs.'/entity/'.$filename, $destination.'/entity/'.Arr::get($data, 'manager')->getName().'/'.$filename, [
                        'data' => $data,
                    ]);
                }
            }
        }
    }

    /**
     * Parse content.
     *
     * @param string $content
     *
     * @return string
     */
    public function parseContent(string $content, array $data = []): string
    {
        $twig = new \Twig_Environment(new \Twig_Loader_String());

        return $twig->render($content, $data);
    }

    /**
     * Add a data.
     *
     * @param string $package
     * @param string $name
     * @param array  $data
     */
    public function addData(string $package, string $name, array $data)
    {
        $classManager = Arr::get($data, 'manager');
        $faker = Arr::get($data, 'faker');
        $className = basename(str_replace('\\', '/', Arr::get($data, 'model')));

        $manager = new $classManager();
        $entity = $manager->newEntity();

        $errors = [];

        foreach ($manager->getExceptions() as $code => $exception) {
        }

        foreach ($manager->getAttributes() as $attribute) {
            foreach ($attribute->getExceptions() as $code => $exception) {
                if ($code === Tokens::NOT_DEFINED) {
                    if ($attribute->getRequired()) {
                        $errors[] = $attribute->newException($code, null);
                    }
                } elseif ($code === Tokens::NOT_UNIQUE) {
                    if ($attribute->getUnique()) {
                        $errors[] = $attribute->newException($code, null);
                    }
                } elseif ($code === Tokens::NOT_VALID) {
                    if ($attribute->getFillable()) {
                        $errors[] = $attribute->newException($code, null);
                    }
                } elseif ($code === Tokens::NOT_AUTHORIZED) {
                    if ($attribute->getFillable()) {
                        $errors[] = $attribute->newException($code, null);
                    }
                } else {
                    $errors[] = $attribute->newException($code, null);
                }
            }
        }

        $permissions = array_values($manager->getAuthorizer()->getPermissions());

        foreach ($manager->getAttributes() as $attribute) {
            $permissions = array_merge($permissions, array_values($attribute->getPermissions()));
        }

        $this->data[$className] = [
            'className'                              => $className,
            'name'                                   => $name,
            'components'                             => $data,
            'package'                                => $package,
            'manager'                                => $manager,
            'entity'                                 => $entity,
            'instance_shortname'                     => (new \ReflectionClass($manager))->getShortName(),
            'errors'                                 => $errors,
            'permissions'                            => $permissions,
            'parameters'                             => $faker::make()->parameters()->toArray(),
            'parameters_formatted'                   => $this->var_export54($faker::make()->parameters()->toArray()),
        ];
    }

    protected function configure()
    {
        $this
            ->setDescription('Generate the documentation for the library.')
            ->addOption('dir', null, InputOption::VALUE_REQUIRED, 'Target directory', getcwd())
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

        $package = $composer->extra()->amethyst->package;
        $namespace = $composer->extra()->amethyst->namespace;

        $generator = new DocumentGenerator();

        $stubs->generateNewFiles([
            'package' => 'package',
        ], __DIR__.'/../../stubs/doc/library/index.md', $input->getOption('dir').'/docs');
    }
}
