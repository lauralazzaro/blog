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

    protected function dbConnection(): \PDO
    {
        $database = new Db();

        return $database->connection();
    }
}