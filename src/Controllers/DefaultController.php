<?php

namespace NewsTicker\Controllers;

use NewsTicker\Kernel\Controller;
use NewsTicker\Kernel\RedirectResponse;

class DefaultController extends Controller
{
    /**
     * @return RedirectResponse
     * @throws \Exception
     */
    public function indexAction()
    {
        return new RedirectResponse($this->generateUrl('twitter_statuses'));
    }
}