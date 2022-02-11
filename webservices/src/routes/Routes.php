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
            echo 'home page';
        }, 'home');


        self::routesPosts();
//        self::routesComments();
        self::routesUsers();

        $match = self::$router->match();

        if( is_array($match) && is_callable( $match['target'] ) ) {
            call_user_func_array( $match['target'], $match['params'] );
        } else {
            // no route was matched
            header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
        }
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

        self::$router->map('GET', '/posts/post/[:id]', function ($id) {
            $pagePosts = new Ctrl\Post(self::$logger, self::$settings);
            $pagePosts->getOnePost($id);
        }, 'getonepost');

        self::$router->map('PUT', '/posts/post/[:id]', function ($id) {
            $pagePosts = new Ctrl\Post(self::$logger, self::$settings);
            $pagePosts->updateOnePost($id);
        }, 'updateonepost');

        self::$router->map('DELETE', '/posts/post/[:id]', function ($id) {
            $pagePosts = new Ctrl\Post(self::$logger, self::$settings);
            $pagePosts->deleteOnePost($id);
        }, 'deleteonepost');
    }

//    public static function routesComments()
//    {
//        self::$router->map('POST', '/comments/comment/', function () {
//            $pageComments = new Ctrl\Comment();
//            $pageComments->createComment();
//        }, 'home');
//
//        self::$router->map('GET', '/comments/comments', function () {
//            $pageComments = new Ctrl\Comment();
//            $pageComments->getAllComment();
//        }, 'getallcomments');
//
//        self::$router->map('PUT', '/comments/comment/[:id]', function ($id) {
//            $pageComments = new Ctrl\Comment();
//            $pageComments->approveOneComment($id);
//        }, 'updateonecomment');
//
//        self::$router->map('DELETE', '/comments/comment/[:id]', function ($id) {
//            $pageComments = new Ctrl\Comment();
//            $pageComments->deleteOneComment($id);
//        }, 'deleteonecomment');
//    }

    public static function routesUsers()
    {}

}