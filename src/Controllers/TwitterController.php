<?php

namespace NewsTicker\Controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use Abraham\TwitterOAuth\TwitterOAuthException;
use NewsTicker\Kernel\Controller;
use NewsTicker\Kernel\JsonResponse;
use NewsTicker\Kernel\RedirectResponse;
use NewsTicker\Kernel\Response;

class TwitterController extends Controller
{
    /**
     * @return RedirectResponse
     * @throws TwitterOAuthException
     * @throws \Exception
     */
    public function loginAction()
    {
        /** @var TwitterOAuth $twitterOauth */
        $twitterOauth = $this->getService('twitter_oauth');

        $requestToken = $twitterOauth->oauth('oauth/request_token', [
                'oauth_callback' => $this->generateUrl('twitter_callback'),
            ]
        );

        $session = $this->getSession();

        $session->set('oauth_token', $requestToken['oauth_token']);
        $session->set('oauth_token_secret', $requestToken['oauth_token_secret']);

        // generate the URL to make request to authorize our application
        $url = $twitterOauth->url(
            'oauth/authorize', [
                'oauth_token' => $requestToken['oauth_token']
            ]
        );

        return new RedirectResponse($url);
    }

    /**
     * @throws \Exception
     */
    public function logoutAction()
    {
        $session = $this->getSession();
        $session->remove('oauth_token');
        $session->remove('oauth_token_secret');

        return new RedirectResponse($this->generateUrl('home_page'));
    }

    /**
     * @return RedirectResponse
     * @throws \Exception
     */
    public function callbackAction() {

        $session = $this->getSession();
        $oauthVerifier = $this->request->getQueryParameter('oauth_verifier');

        if (is_null($oauthVerifier) || is_null($session->get('oauth_token')) || is_null($session->get('oauth_token_secret'))) {
            // something's missing, go and login again
            return new RedirectResponse($this->generateUrl('twitter_login'));
        }

        /** @var TwitterOAuth $twitterOauth */
        $twitterOauth = $this->getService('twitter_oauth');
        $twitterOauth->setOauthToken($session->get('oauth_token'), $session->get('oauth_token_secret'));

        // request user token
        $token = $twitterOauth->oauth('oauth/access_token', [
                'oauth_verifier' => $oauthVerifier,
            ]
        );

        $session->set('oauth_token', $token['oauth_token']);
        $session->set('oauth_token_secret', $token['oauth_token_secret']);

        return new RedirectResponse($this->generateUrl('twitter_statuses'));
    }

    /**
     * @throws \Exception
     */
    public function statusesAction()
    {
        $session = $this->getSession();

        /** @var TwitterOAuth $twitterOauth */
        $twitterOauth = $this->getService('twitter_oauth');

        // connect with application token
        $twitterOauth->setOauthToken($session->get('oauth_token'), $session->get('oauth_token_secret'));

        $twitterOauth->get('account/verify_credentials');
        $isTokenValid = $twitterOauth->getLastHttpCode() == 200;

        if (!$isTokenValid) {
            return new RedirectResponse($this->generateUrl('twitter_login'));
        }

        // request statuses
        $statuses = $twitterOauth->get('statuses/home_timeline', [
            'count' => $this->getParameter('twitter_statuses_count') + 1,
        ]);

        $statusesArr = [];

        foreach ($statuses as $key => $status) {
            $statusArr = get_object_vars($status);
            $statusUserArr = get_object_vars($statusArr['user']);
            $statusesArr[$key]['user_name'] = $statusUserArr['name'];
            $statusesArr[$key]['user_screen_name'] = $statusUserArr['screen_name'];
            $statusesArr[$key]['created_at'] = (new \DateTime($statusArr['created_at']))->format('Y-m-d H:i:s');
            $statusesArr[$key]['text'] = $statusArr['text'];
        }

        return new JsonResponse($statusesArr);
    }
}