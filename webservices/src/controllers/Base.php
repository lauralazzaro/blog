<?php

namespace LL\WS\controllers;

use Monolog\Logger;

class Base
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


}