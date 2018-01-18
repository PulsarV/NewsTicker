<?php

namespace NewsTicker\Kernel;

use Symfony\Component\Yaml\Yaml;

class Router
{
    /** @var array $routes */
    private $routes = [];

    /** @var Request $request */
    private $request;

    /** @var string $name */
    private $name = '';

    /** @var Controller $controller */
    private $controller;

    /** @var string $action */
    private $action = '';

    /**
     * Router constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Router
     * @throws \Exception
     */
    public function importConfig()
    {
        $this->routes = Yaml::parse(file_get_contents(Container::getInstance()->getParameter('config_dir').'/routing.yml'));

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function handleRequest()
    {
        foreach ($this->routes as $name => $params) {
            if ($params['route'] === $this->request->getUri()) {
                $this->name = $name;

                $suppotedMethods = array_map('strtoupper', $this->routes[$name]['method']);
                if (!in_array($this->request->getMethod(), $suppotedMethods)) {
                    throw new \Exception('Unsupported request method "'.$this->request->getMethod().'"', 405);
                }

                $controllerClass = 'NewsTicker\Controllers\\'.ucfirst($this->routes[$name]['controller'].'Controller');
                if (class_exists($controllerClass)) {
                    $this->controller = new $controllerClass($this->request);
                } else {
                    throw new \Exception('Controller "'.$controllerClass.'" not found', 500);
                }

                $controllerAction = $this->routes[$name]['action'].'Action';
                if (method_exists($controllerClass, $controllerAction)) {
                    $this->action = $controllerAction;
                }  else {
                    throw new \Exception('Action "'.$controllerAction.'" not found in controller "'.$controllerClass.'"', 500);
                }

                break;
            }
        }

        if (!($this->name)) {
            throw new \Exception('Route "'.$this->request->getUri().'" is not defined', 404);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Controller
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $routeName
     * @return string
     * @throws \Exception
     */
    public function generateUrl($routeName)
    {
        if (!isset($this->routes[$routeName])) {
            throw new \Exception('Route name"'.$routeName.'" is not defined');
        }

        $baseUrl = $this->request->getScheme().'://'.$this->request->getHost().($this->request->getPort() != 80 ? $this->request->getPort() : '');

        return $baseUrl . $this->routes[$routeName]['route'];
    }
}