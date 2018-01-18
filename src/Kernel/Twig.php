<?php

namespace NewsTicker\Kernel;

use Twig_Environment;
use Twig_Loader_Filesystem;

class Twig
{
    /** @var Twig_Environment */
    private $twigEngine;

    /**
     * Twig constructor.
     * @param $templatesDir
     */
    public function __construct($templatesDir)
    {
        $loader = new Twig_Loader_Filesystem($templatesDir);
        $this->twigEngine = new Twig_Environment($loader);
    }

    /**
     * @param $template
     * @param array $parameters
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render($template, array $parameters = [])
    {
        return $this->twigEngine->render($template, $parameters);
    }
}