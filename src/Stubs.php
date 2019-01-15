<?php

namespace Railken\Amethyst\Cli;

use Symfony\Component\Console\Output\OutputInterface;
use Railken\Template\Generators;
use Railken\Amethyst\Cli\Twig\AppExtension;

class Stubs
{

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    public $output;

    /**
     * @var \Twig_Environment
     */
    public $twig;


    /**
     * Create a new instance.
     *
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;

        $this->twig = new \Twig_Environment(new \Twig_Loader_Array(array()));

        $this->twig->addExtension(new AppExtension());
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
     * @param array  $data
     * @param string $source
     * @param string $directory
     */
    public function generateNewFiles(array $data, string $source, string $directory)
    {
        $files = self::rglob($source.'/{,.}[!.,!..]*', GLOB_MARK | GLOB_BRACE);

        foreach ($files as $file) {
            if (!is_dir($file)) {
                $newfile = str_replace($source, '', $file);


                $content = $this->twig->createTemplate(file_get_contents($file))->render($data);
                $newfile = $this->twig->createTemplate($this->escapedFilename($newfile))->render($data);

                $to = $directory.$newfile;

                if (!file_exists(dirname($to))) {
                    mkdir(dirname($to), 0755, true);
                }

                file_put_contents($to, $content);

                $this->output->writeln(sprintf('<info>Generated: %s</info>', $to));
            }
        }
    }

    public function escapedFilename(string $filename): string
    {
        $filename = str_replace("__", "|", $filename);
        $filename = str_replace("|--", "{{", $filename);
        $filename = str_replace("--|", "}}", $filename);

        return $filename;
    }
}