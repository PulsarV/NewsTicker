<?php

namespace NewsTicker\Kernel;

class Response implements ResponseInterface
{
    /** @var array $data */
    private $data;

    /** @var int $statusCode */
    protected $statusCode;

    /**
     * JsonResponse constructor.
     * @param string $data
     * @param int $statusCode
     */
    public function __construct($data, $statusCode = 200)
    {
        $this->data = $data;
        $this->statusCode = $statusCode;
    }

    public function send()
    {
        http_response_code($this->statusCode);
        echo $this->data;
    }
}