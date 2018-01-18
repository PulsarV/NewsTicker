<?php

namespace NewsTicker\Kernel;

use Abraham\TwitterOAuth\TwitterOAuth;
use Symfony\Component\Yaml\Yaml;

class Kernel
{
    /** @var Container $container */
    private $container;

    /** @var Request $request */
    private $request;

    public function __construct(Request $request)
    {
        $this->container = Container::getInstance();
        $this->request = $request;
    }

    /**
     * @return Kernel
     * @throws \Exception
     */
    private function importParameters()
    {
        $parameters = Yaml::parse(file_get_contents($this->getConfigDir().'/parameters.yml'))['parameters'];

        $this->container->addParameter('root_dir', $this->getRootDir());
        $this->container->addParameter('config_dir', $this->getConfigDir());
        $this->container->addParameter('templates_dir', $this->getTemplatesDir());

        foreach ($parameters as $parameterName => $parameterValue) {
            $this->container->addParameter($parameterName, $parameterValue);
        }

        return $this;
    }

    /**
     * @return Kernel
     * @throws \Exception
     */
    private function importServices()
    {
        $services = [
            'session' => new Session(),
            'router' => new Router($this->request),
            'twig' => new Twig($this->container->getParameter('templates_dir')),
            'twitter_oauth' => new TwitterOAuth($this->container->getParameter('twitter_oauth_key'), $this->container->getParameter('twitter_oauth_secret')),
        ];

        foreach ($services as $serviceName => $serviceInstance) {
            $this->container->addService($serviceName, $serviceInstance);
        }

        return $this;
    }

    /**
     * @return string
     */
    private function getRootDir()
    {
        return __DIR__.'/../..';
    }

    /**
     * @return string
     */
    private function getConfigDir()
    {
        return $this->getRootDir().'/config';
    }

    /**
     * @return string
     */
    private function getTemplatesDir()
    {
        return __DIR__.'/../Views';
    }

    /**
     * @return mixed
     */
    public function handleRequest()
    {
        try {
            $this->importParameters();
            $this->importServices();
            /** @var Router $router */
            $router = $this->container->getService('router');
            $router->importConfig();
            $router->handleRequest();
            $controller = $router->getController();
            $action = $router->getAction();
            $response = $controller->$action();
            if (!$response instanceof $response) {
                throw new \Exception('Unsupported response type');
            }

            return $response;
        } catch (\Exception $e) {
            echo 'ERROR: '.$e->getMessage();
        }
    }
}