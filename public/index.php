<?php

use NewsTicker\Kernel\Kernel;
use NewsTicker\Kernel\Request;
use NewsTicker\Kernel\ResponseInterface;

require __DIR__.'/../vendor/autoload.php';

$request = Request::getInstance();
$kernel = new Kernel($request);
/** @var ResponseInterface $response */
$response = $kernel->handleRequest();
$response->send();