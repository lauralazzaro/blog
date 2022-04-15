<?php

namespace LL\WS\controllers;

use LL\WS\Models as mdl;
use LL\WS\classes as cls;

class User extends Base
{
    private mdl\User $modelUser;

    public function __construct($logger, $config)
    {
        parent::__construct($logger, $config);

        $this->modelUser = new mdl\User($this->logger);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function signup()
    {
        $this->logger->info('signup');

        $body = $this->getBodyRequest();

        $user = new cls\User();

        $user->setEmail($body->email);
        $user->setPassword($body->password);
        $user->setUsername($body->username);

        $this->modelUser->insertUser($user);

        echo true;
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function login()
    {
        $this->logger->info('login');

        $body = $this->getBodyRequest();

        $user = new cls\User();

        $user->setEmail($body->email);
        $user->setPassword($body->password);

        $dbArray = $this->modelUser->selectUser($user);

        if($dbArray) {
            $token = $this->genererateToken($dbArray);

            $this->modelUser->createTokenLogin($dbArray['id'], $token);

            $dbArray['token'] = $token;

            echo json_encode($dbArray);
        }
    }

    /**
     * @param $userId
     * @return bool
     * @throws \Exception
     */
    public function logout($userId)
    {
        $this->logger->info('logout');

        $this->modelUser->deleteTokenLogin($userId);

        return true;
    }

    /**
     * @param $token
     * @return array
     * @throws \Exception
     */
    public function getUserFromToken($token)
    {
        $this->logger->info('verify token');

        $user = $this->modelUser->getUserFromToken($token);

        return $user;
    }


}