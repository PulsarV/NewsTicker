<?php

namespace NewsTicker\Kernel;

interface ResponseInterface
{
    /**
     * @return mixed
     */
    public function send();
}