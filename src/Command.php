<?php

namespace Railken\Amethyst\Skeleton;

use Doctrine\Common\Inflector\Inflector;

class Command
{
    public $inflector;

    public static function main(array $argv = [])
    {
        $command = new static();
        $command->inflector = new Inflector();

        $command->handle($argv);
    }

    public function rglob($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, $this->rglob($dir.'/'.basename($pattern), $flags));
        }

        return $files;
    }

    public function handle(array $argv = [])
    {
        $package = $argv[1];

        $source = __DIR__.'/../stubs';

        $files = self::rglob($source.'/{,.}[!.,!..]*', GLOB_MARK | GLOB_BRACE);

        $destination = getcwd().'/amethyst-'.$package;

        print_r("\n\nGenerating...\n");

        foreach ($files as $file) {
            if (!is_dir($file)) {
                $content = file_get_contents($file);

                $content = self::replace($package, $content);
                $newfile = self::replace($package, str_replace($source, '', $file));

                $to = $destination.$newfile;

                if (!file_exists(dirname($to))) {
                    mkdir(dirname($to), 0755, true);
                }

                file_put_contents($to, $content);

                print_r(sprintf("Generated: %s \n", $to));
            }
        }
    }

    public function replace(string $package, string $content)
    {
        $content = str_replace('foo_bars', $this->inflector->pluralize($this->inflector->tableize($package)), $content);
        $content = str_replace('foo_bar', $this->inflector->tableize($package), $content);
        $content = str_replace('foo-bars', str_replace('_', '-', $this->inflector->pluralize($this->inflector->tableize($package))), $content);
        $content = str_replace('foo-bar', str_replace('_', '-', $this->inflector->tableize($package)), $content);
        $content = str_replace('FooBars', $this->inflector->pluralize($this->inflector->classify($package)), $content);
        $content = str_replace('FooBar', $this->inflector->classify($package), $content);

        return $content;
    }
}
