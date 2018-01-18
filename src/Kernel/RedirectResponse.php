<?php

namespace NewsTicker\Kernel;

class RedirectResponse implements ResponseInterface
{
    /** @var string $url */
    protected $url;

    /**
     * RedirectResponse constructor.
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    public function send()
    {
        header('Location: '. $this->url);
    }
}