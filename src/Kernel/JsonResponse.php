<?php

namespace NewsTicker\Kernel;

class JsonResponse implements ResponseInterface
{
    /** @var array $data */
    protected $data;
    /** @var int $statusCode */
    protected $statusCode;

    /**
     * JsonResponse constructor.
     * @param array $data
     * @param int $statusCode
     */
    public function __construct(array $data, $statusCode = 200)
    {
        $this->data = $data;
        $this->statusCode = $statusCode;
    }

    /**
     * @return mixed|void
     */
    public function send()
    {
        http_response_code($this->statusCode);
        header('Content-Type: application/json');
        echo json_encode($this->data);
    }
}