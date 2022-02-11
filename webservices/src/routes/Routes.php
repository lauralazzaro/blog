<?php

namespace LL\WS\Routes;

use LL\WS\Controllers as Ctrl;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

final class Routes
{
    /**
     * @var Logger
     */
    private static Logger $logger;

    /**
     * @var array
     */
    public static array $settings;

    /**
     * @var \AltoRouter
     */
    private static \AltoRouter $router;


    public static function init()
    {
        date_default_timezone_set('Europe/Paris');
        self::$logger = new Logger('log');
        self::$logger->pushHandler(new RotatingFileHandler('logs/blog-ll-log.log', 10, Logger::DEBUG));
    }

    public static function route()
    {
        self::$router = new \AltoRouter();
        self::$router->setBasePath('/bloglauralazzaro/webservices/api/v1');

        self::$router->map('GET', '/', function () {
        }, 'home');


        self::routesPosts();

        $match = self::$router->match();

        if( !is_array($match) && !is_callable($match['target'])) {

            $serverProtocol = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL', FILTER_SANITIZE_STRING);
            header(  $serverProtocol . ' 404 Not Found');
            return;
        }

        call_user_func_array( $match['target'], $match['params'] );
    }

    /**
     * Routes for posts
     *
     * @return void
     * @throws \Exception
     */
    public static function routesPosts()
    {
        self::$router->map('POST', '/posts/post', function () {
            $pagePosts = new Ctrl\Post(self::$logger, self::$settings);
            $pagePosts->createPost();
        }, 'createpost');

        self::$router->map('GET', '/posts/posts', function () {
            $pagePosts = new Ctrl\Post(self::$logger, self::$settings);
            $pagePosts->getAllPost();
        }, 'getallposts');

        self::$router->map('GET', '/posts/post/[:idPost]', function ($idPost) {
            $pagePosts = new Ctrl\Post(self::$logger, self::$settings);
            $pagePosts->getOnePost($idPost);
        }, 'getonepost');

        self::$router->map('PUT', '/posts/post/[:idPost]', function ($idPost) {
            $pagePosts = new Ctrl\Post(self::$logger, self::$settings);
            $pagePosts->updateOnePost($idPost);
        }, 'updateonepost');

        self::$router->map('DELETE', '/posts/post/[:idPost]', function ($idPost) {
            $pagePosts = new Ctrl\Post(self::$logger, self::$settings);
            $pagePosts->deleteOnePost($idPost);
        }, 'deleteonepost');
    }

}