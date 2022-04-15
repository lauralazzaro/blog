<?php

namespace LL\WS\models;

use Monolog\Logger;

abstract class Base
{
    protected Logger $logger;
    protected \PDO $dbConnection;

    public function __construct($logger)
    {
        $this->logger = $logger;
        $this->dbConnection = $this->dbConnection();
    }

    private function dbConnection(): \PDO
    {
        $config = include realpath('../_settings/settings.php');

        $dbhost = $config['db']['dbhost'];
        $dbport = $config['db']['dbport'];
        $dbname = $config['db']['dbname'];
        $dbuser = $config['db']['dbuser'];
        $dbpassword = $config['db']['dbpassword'];

        return new \PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8;port=$dbport", $dbuser, $dbpassword, []);
    }
}