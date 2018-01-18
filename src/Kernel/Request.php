<?php

namespace NewsTicker\Kernel;

class Request
{
    /** @var Request $request */
    private static $request = null;

    /** @var string $scheme */
    private static $scheme;

    /** @var string $host */
    private static $host;

    /** @var string $port */
    private static $port;

    /** @var string $uri */
    private static $uri;

    /** @var string $method */
    private static $method;

    /** @var string $queryString */
    private static $queryString;

    /** @var array $queryParameters */
    private static $queryParameters = [];

    /**
     * Request constructor.
     */
    private function __construct()
    {
        self::$scheme = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : '';
        self::$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        self::$port = isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : '';
        self::$uri = isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : '';
        self::$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
        self::$queryString = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
        if (self::$queryString) {
            $queryParams = explode('&', self::$queryString);
            foreach ($queryParams as &$queryParam) {
                $paramsArr = explode('=', $queryParam);
                self::$queryParameters[$paramsArr[0]] = isset($paramsArr[1]) ? $paramsArr[1] : '';
            }
        }
    }

    /**
     *
     */
    protected function __clone()
    {
    }

    /**
     * @return Request
     */
    public static function getInstance()
    {
        if (is_null(self::$request)) {
            self::$request = new self();
        }

        return self::$request;
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return self::$scheme;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return self::$host;
    }

    /**
     * @return string
     */
    public function getPort()
    {
        return self::$port;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return self::$uri;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::$method;
    }

    /**
     * @return string
     */
    public function getQueryString()
    {
        return self::$queryString;
    }

    /**
     * @param string $parameterName
     * @return array|null
     */
    public function getQueryParameter($parameterName)
    {
        return isset(self::$queryParameters[$parameterName]) ? self::$queryParameters[$parameterName] : null;
    }
}