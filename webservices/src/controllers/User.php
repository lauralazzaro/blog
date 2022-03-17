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

        $dbArray = $this->modelUser->selectUser($user);

        echo json_encode($dbArray);
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

        $token = $this->genererateToken($dbArray);
        $dbArray['token'] = $token;

        echo json_encode($dbArray);
    }

    public function isAdmin($userId)
    {
        $this->logger->info('check if admin');

        $role = $this->modelUser->selectUserById($userId);

        if($role['role'] === 'user'){
            throw new \Exception('403.User not authorized');
        }

        return true;
    }
}