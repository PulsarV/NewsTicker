<?php

namespace NewsTicker\Kernel;

final class Container
{
    /** @var array $parameters */
    private static $parameters = [];

    /** @var array $services */
    private static $services = [];

    /** @var Container $container */
    private static $container = null;

    /**
     * Container constructor.
     */
    private function __construct()
    {
    }

    /**
     *
     */
    protected function __clone()
    {
    }

    /**
     * @return Container
     */
    public static function getInstance()
    {
        if (is_null(self::$container)) {
            self::$container = new self();
        }

        return self::$container;
    }

    /**
     * @param $parameterName
     * @param $parameterValue
     * @throws \Exception
     */
    public function addParameter($parameterName, $parameterValue)
    {
        if (!$parameterName) {
            throw new \Exception('Parameter name can not be empty');
        }

        if (isset(self::$parameters[$parameterName])) {
            throw new \Exception('Parameter "'.$parameterName.'" already exists in container');
        }

        self::$parameters[$parameterName] = $parameterValue;
    }

    /**
     * @param $parameterName
     * @return mixed
     * @throws \Exception
     */
    public function getParameter($parameterName)
    {
        if (!isset(self::$parameters[$parameterName])) {
            throw new \Exception('Parameter "'.$parameterName.'" not found');
        }

        return self::$parameters[$parameterName];
    }

    /**
     * @param $serviceName
     * @param $serviceInstance
     * @throws \Exception
     */
    public function addService($serviceName, $serviceInstance)
    {
        if (!$serviceName) {
            throw new \Exception('Service name can not be empty');
        }

        if (!is_object($serviceInstance)) {
            throw new \Exception('Service must be instance of any class');
        }

        if (isset(self::$services[$serviceName])) {
            throw new \Exception('Service "'.$serviceName.'" already exists in container');
        }

        self::$services[$serviceName] = $serviceInstance;
    }

    /**
     * @param $serviceName
     * @return mixed
     * @throws \Exception
     */
    public function getService($serviceName)
    {
        if (!isset(self::$services[$serviceName])) {
            throw new \Exception('Service "'.$serviceName.'" not found');
        }

        return self::$services[$serviceName];
    }
}