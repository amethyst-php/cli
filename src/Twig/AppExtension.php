<?php

namespace Railken\Amethyst\Cli\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Doctrine\Common\Inflector\Inflector;

class AppExtension extends AbstractExtension
{
    /**
     * @var \Doctrine\Common\Inflector\Inflector
     */
    public $inflector;

    public function __construct()
    {
        $this->inflector = new Inflector();
    }

    public function getFilters()
    {
        return [
            new TwigFilter('classify', [$this, 'classify']),
            new TwigFilter('tableize', [$this, 'tableize']),
            new TwigFilter('pluralize', [$this, 'pluralize']),
            new TwigFilter('kebab', [$this, 'kebab']),
            new TwigFilter('escapeSlash', [$this, 'escapeSlash']),

        ];
    }

    public function classify(string $string = null)
    {
        return $this->inflector->classify($string);
    }

    public function tableize(string $string = null)
    {
        return str_replace('-', '_', $this->inflector->tableize($string));
    }

    public function kebab(string $string = null)
    {
        return str_replace('_', '-', $this->inflector->tableize($string));
    }

    public function pluralize(string $string = null)
    {
        return $this->inflector->pluralize($string);
    }

    public function escapeSlash(string $string = null)
    {
        return str_replace("\\", "\\\\", $string);
    }
}


