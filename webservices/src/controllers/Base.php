<?php

namespace LL\WS\controllers;

use Monolog\Logger;
use ReallySimpleJWT\Token;
use ReallySimpleJWT\Parse;
use ReallySimpleJWT\Jwt;
use ReallySimpleJWT\Decode;
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

    protected function validateToken($token)
    {
        $tokens = new Tokens();

        if (!$tokens->validate($token, $this->settings['token']['secret'])) {
            throw new \Exception('Invalid token');
        }

        $jwt = new Jwt($token, $this->settings['token']['secret']);

        $parse = new Parse($jwt, new Decode());

        $parsed = $parse->parse();
        $payload = $parsed->getPayload();

        return (int)$payload['user_id'];

    }


}