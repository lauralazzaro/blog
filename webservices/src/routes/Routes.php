<?php

namespace LL\WS\Routes;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

final class Routes
{
    /**
     * @var Logger
     */
    private static $logger;

    /**
     * @var array
     */
    public static $settings;

    /**
     * @var \AltoRouter
     */
    private static $router;


    public static function init()
    {
        date_default_timezone_set('Europe/Paris');
        self::$logger = new Logger('log');
        self::$logger->pushHandler(new RotatingFileHandler('../logs/blog-ll-log.log', 10, Logger::DEBUG));
    }

    public static function route()
    {
        self::$router = new \AltoRouter();
        self::$router->setBasePath('/bloglauralazzaro/webservices');

        self::$router->map('GET', '/', function () {
            echo 'home page';
        }, 'home');

        self::$router->map('GET', '/posts/posts', function () {
            echo 'all posts';
        }, 'getallposts');

        self::$router->map('GET', '/posts/post/[:id]', function ($id) {
            echo 'get one post ' . $id;
        }, 'getonepost');

        self::$router->map('PUT', '/posts/post/[:id]', function ($id) {
            echo 'update one post ' . $id;
        }, 'updateonepost');

        self::$router->map('DELETE', '/posts/post/[:id]', function ($id) {
            echo 'delete one post ' . $id;
        }, 'deleteonepost');

        self::routesPosts();
        self::routesComments();
        self::routesUsers();

        $match = self::$router->match();

        if( is_array($match) && is_callable( $match['target'] ) ) {
            call_user_func_array( $match['target'], $match['params'] );
        } else {
            // no route was matched
            header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
        }
    }

    public static function routesPosts()
    {}

    public static function routesComments()
    {}

    public static function routesUsers()
    {}

}