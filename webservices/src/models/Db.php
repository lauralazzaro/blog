<?php

namespace LL\WS\Models;

class Db
{

    public static \PDO $dbConnection;

    public static function connection()
    {
        $config = self::getSettings();

        $dbhost = $config['db']['dbhost'];
        $dbport = $config['db']['dbport'];
        $dbname = $config['db']['dbname'];
        $dbuser = $config['db']['dbuser'];
        $dbpassword = $config['db']['dbpassword'];

        self::$dbConnection = new \PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8;port=$dbport",$dbuser,$dbpassword, []);
    }

    private static function getSettings(){

        return include realpath('../_settings/settings.php');
    }
}