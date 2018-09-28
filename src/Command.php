<?php

namespace Railken\Amethyst\Skeleton;

class Command
{
    public static function rglob($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, self::rglob($dir.'/'.basename($pattern), $flags));
        }

        return $files;
    }

    public static function main(array $argv = [])
    {
        $slug = $argv[1];
        $model = $argv[2];

        $source = __DIR__.'/../stubs';

        $files = self::rglob($source.'/*');

        $destination = getcwd().'/amethyst-'.$slug;

        print_r($files);

        foreach ($files as $file) {
            if (!is_dir($file)) {
                $content = file_get_contents($file);

                $content = str_replace('foo', $slug, $content);
                $content = str_replace('Foo', $model, $content);

                $newfile = str_replace($source, '', $file);
                $newfile = str_replace('foo', $slug, $newfile);
                $newfile = str_replace('Foo', $model, $newfile);

                $to = $destination.$newfile;

                if (!file_exists(dirname($to))) {
                    mkdir(dirname($to), 0755, true);
                }

                file_put_contents($to, $content);
            }
        }
    }
}
