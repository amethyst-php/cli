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
use Eloquent\Composer\Configuration\ConfigurationReader;
use Orchestra\Testbench\Concerns\CreatesApplication;

class LibraryDocumentationCommand extends Command
{
    use Concerns\Export;
    use CreatesApplication;

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

        $namePackage = $composer->extra()->amethyst->package;

        $app = $this->createApplication();

        foreach ($composer->extra()->laravel->providers as $provider) {
            $provider = (new $provider($app));
            $provider->register();
            $provider->boot();
        }

        $entities = [];

        foreach ((array) Config::get('amethyst.'.$namePackage.'.data') as $nameData => $data) {
            if (Arr::get($data, 'manager')) {
                $entities[] = $this->addData($namePackage, $nameData, $data);
            }
        }

        $stubs->generateNewFiles([
            'data' => $entities,
            'composerPackageName' => $composer->name(),
        ], __DIR__.'/../stubs/docs/library', $input->getOption('dir').'/docs');

        foreach ($entities as $entity) {

            $stubs->generateNewFiles([
                'data' => $entity,
            ], __DIR__.'/../stubs/docs/entity', $input->getOption('dir').'/docs/data/'.$entity['manager']->getName());
        }
    }

    /**
     * Add a data.
     *
     * @param string $package
     * @param string $name
     * @param array  $data
     */
    public function addData(string $package, string $name, array $data): array
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

        return [
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

    public function getEnvironmentSetUp($app)
    {
        return;
    }

}
