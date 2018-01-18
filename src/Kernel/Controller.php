<?php

namespace NewsTicker\Kernel;

class Controller
{
    /** @var Request $request */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function getParameter($name)
    {
        return Container::getInstance()->getParameter($name);
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function getService($name)
    {
        return Container::getInstance()->getService($name);
    }

    /**
     * @return Session
     * @throws \Exception
     */
    public function getSession()
    {
        return $this->getService('session');
    }

    /**
     * @param $template
     * @param array $parameters
     * @return string
     * @throws \Exception
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render($template, array $parameters = [])
    {
        /** @var Twig $twig */
        $twig = $this->getService('twig');

        return $twig->render($template, $parameters);
    }

    /**
     * @param string $routeName
     * @return string
     * @throws \Exception
     */
    public function generateUrl($routeName)
    {
        return $this->getService('router')->generateUrl($routeName);
    }
}