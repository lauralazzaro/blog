<?php

namespace LL\WS\controllers;

use Monolog\Logger;
use ReallySimpleJWT\Tokens;

abstract class Base
{

    protected Logger $logger;

    protected array $settings;


    public function __construct($logger, $settings)
    {
        $this->logger = $logger;
        $this->settings = $settings;

        header('Content-type: application/json');
    }

    protected function getBodyRequest($isArray = false)
    {
        $json = file_get_contents('php://input');

        return json_decode($json, $isArray);
    }

    protected function genererateToken($user)
    {
        $tokens = new Tokens();
        $token =  $tokens->create('user_id', $user['id'], $this->settings['token']['secret'], $this->settings['token']['expiration'], $this->settings['token']['issuer']);
        return $token->getToken();
    }
}