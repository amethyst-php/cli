<?php

namespace Railken\Amethyst\Cli\Generator;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Railken\Lem\Tokens;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class DocumentGenerator
{
    /**
     * @var array
     */
    protected $data = [];

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

    public function generateFile(string $source, string $destination, array $data = [])
    {
        if (!file_exists(dirname($destination))) {
            mkdir(dirname($destination), 0755, true);
        }

        $content = file_get_contents($source);

        $content = $this->parseContent((string) $content, $data);

        file_put_contents($destination, $content);
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

    public function var_export54($var, $indent = '')
    {
        switch (gettype($var)) {
            case 'string':
                return '"'.addcslashes($var, "\\\$\"\r\n\t\v\f").'"';
            case 'array':
                $indexed = array_keys($var) === range(0, count($var) - 1);
                $r = [];
                foreach ($var as $key => $value) {
                    $r[] = "$indent    "
                         .($indexed ? '' : $this->var_export54($key).' => ')
                         .$this->var_export54($value, "$indent    ");
                }

                return "[\n".implode(",\n", $r)."\n".$indent.']';
            case 'boolean':
                return $var ? 'TRUE' : 'FALSE';
            default:
                return var_export($var, true);
        }
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
            'className'                   => $className,
            'name'                        => $name,
            'components'                  => $data,
            'package'				                 => $package,
            'manager'                     => $manager,
            'entity'                      => $entity,
            'instance_shortname'          => (new \ReflectionClass($manager))->getShortName(),
            'errors' 	                    => $errors,
            'permissions'	                => $permissions,
            'parameters'                  => $faker::make()->parameters()->toArray(),
            'parameters_formatted'        => $this->var_export54($faker::make()->parameters()->toArray()),
        ];
    }
}
