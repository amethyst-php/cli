<?php

namespace Railken\Amethyst\Skeleton;

use Doctrine\Common\Inflector\Inflector;
use Symfony\Component\Console\Output\OutputInterface;

class Stubs
{
    /**
     * @var \Doctrine\Common\Inflector\Inflector
     */
    public $inflector;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    public $output;

    /**
     * Create a new instance.
     *
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->inflector = new Inflector();
        $this->output = $output;
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
     * @param array  $replace
     * @param string $source
     * @param string $directory
     */
    public function generateNewFiles(array $replace, string $source, string $directory)
    {
        $files = self::rglob($source.'/{,.}[!.,!..]*', GLOB_MARK | GLOB_BRACE);

        foreach ($files as $file) {
            if (!is_dir($file)) {
                $content = file_get_contents($file);
                $newfile = str_replace($source, '', $file);

                $content = $this->replaceArray($replace, $content);
                $newfile = $this->replaceArray($replace, $newfile);

                $to = $directory.$newfile;

                if (!file_exists(dirname($to))) {
                    mkdir(dirname($to), 0755, true);
                }

                file_put_contents($to, $content);

                $this->output->writeln(sprintf('<info>Generated: %s</info>', $to));
            }
        }
    }

    /**
     * @param array  $array
     * @param string $content
     *
     * @return string
     */
    public function replaceArray(array $replace, string $content): string
    {
        foreach ($replace as $key => $value) {
            $content = $this->replace($key, $value, $content);
        }

        return $content;
    }

    /**
     * @param string $from
     * @param string $to
     * @param string $content
     *
     * @return string
     */
    public function replace(string $from, string $to, string $content): string
    {
        return str_replace($this->normalize($from), $this->normalize($to), $content);
    }

    /**
     * @param string $string
     */
    public function normalize($string)
    {
        return [
            $this->inflector->pluralize($this->inflector->tableize($string)),
            $this->inflector->tableize($string),
            str_replace('-', '_', $this->inflector->pluralize($this->inflector->tableize($string))),
            str_replace('-', '_', $this->inflector->tableize($string)),
            $this->inflector->pluralize($this->inflector->classify($string)),
            $this->inflector->classify($string),
        ];
    }
}
