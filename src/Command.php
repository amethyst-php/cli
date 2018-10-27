<?php

namespace Railken\Amethyst\Skeleton;

use Doctrine\Common\Inflector\Inflector;

class Command
{
    /**
     * @var \Doctrine\Common\Inflector\Inflector
     */
    public $inflector;

    /**
     * @var string
     */
    public $destination;

    /**
     * @param array  $argv
     * @param string $destination
     */
    public static function main(array $argv = [], string $destination)
    {
        $command = new static();
        $command->inflector = new Inflector();

        $command->destination = $destination ? $destination : getcwd();

        if (!isset($argv[1]) || !isset($argv[2])) {
            throw new \InvalidArgumentException('Missing parameter');
        }

        if ($argv[1] === 'new') {
            $command->handlePackage($command->parseParam($argv[2]));
        }

        if ($argv[1] === 'data') {
            if (!isset($argv[3])) {
                throw new \InvalidArgumentException('Missing parameter');
            }

            $command->handleData($command->parseParam($argv[2]), $command->parseParam($argv[3]));
        }
    }

    /**
     * Recursive glob.
     *
     * @param string $pattern
     * @param int    $flags
     *
     * @return array
     */
    public function rglob(string $pattern, int $flags = 0): array
    {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, $this->rglob($dir.'/'.basename($pattern), $flags));
        }

        return $files;
    }

    /**
     * Parse parameter.
     *
     * @param string $param
     *
     * @return string
     */
    public function parseParam(string $param): string
    {
        return str_replace('_', '-', $this->inflector->tableize($this->inflector->classify($param)));
    }

    /**
     * Handle the incoming request.
     *
     * @string $package
     */
    public function handlePackage(string $package)
    {
        $destination = $this->destination.'/amethyst-'.$package;

        $this->generateNewFiles([
            'package-name' => $package,
        ], __DIR__.'/../stubs/package', $destination);
    }

    /**
     * Handle the incoming request.
     *
     * @string $package
     * @string $data
     */
    public function handleData(string $package, string $data)
    {
        $destination = $this->destination;

        $this->generateNewFiles([
            'package-name' => $package,
            'foo-bar'      => $data,
        ], __DIR__.'/../stubs/data', $destination);
    }

    public function generateNewFiles(array $replace, string $source, string $destination)
    {
        $files = self::rglob($source.'/{,.}[!.,!..]*', GLOB_MARK | GLOB_BRACE);

        foreach ($files as $file) {
            if (!is_dir($file)) {
                $content = file_get_contents($file);
                $newfile = str_replace($source, '', $file);

                foreach ($replace as $key => $value) {
                    $content = self::replace($key, $value, $content);
                    $newfile = self::replace($value, $value, $newfile);
                }

                $to = $destination.$newfile;

                if (!file_exists(dirname($to))) {
                    mkdir(dirname($to), 0755, true);
                }

                file_put_contents($to, $content);

                print_r(sprintf("Generated: %s \n", $to));
            }
        }
    }

    public function replace(string $from, string $to, string $content)
    {
        return str_replace([
            $this->inflector->pluralize($this->inflector->tableize($from)),
            $this->inflector->tableize($from),
            str_replace('_', '-', $this->inflector->pluralize($this->inflector->tableize($from))),
            str_replace('_', '-', $this->inflector->tableize($from)),
            $this->inflector->pluralize($this->inflector->classify($from)),
            $this->inflector->classify($from),
        ], [
            $this->inflector->pluralize($this->inflector->tableize($to)),
            $this->inflector->tableize($to),
            str_replace('_', '-', $this->inflector->pluralize($this->inflector->tableize($to))),
            str_replace('_', '-', $this->inflector->tableize($to)),
            $this->inflector->pluralize($this->inflector->classify($to)),
            $this->inflector->classify($to),
        ], $content);
    }
}
