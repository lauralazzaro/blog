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
            echo ('home');
        }, 'home');


        self::routesPosts();
        self::routesUsers();
        self::routesComments();

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

        self::$router->map('PUT', '/posts/post/[:idPost]/update', function ($idPost) {
            $pagePosts = new Ctrl\Post(self::$logger, self::$settings);
            $pagePosts->updateOnePost($idPost);
        }, 'updateonepost');

        self::$router->map('DELETE', '/posts/post/[:idPost]/delete', function ($idPost) {
            $pagePosts = new Ctrl\Post(self::$logger, self::$settings);
            $pagePosts->deleteOnePost($idPost);
        }, 'deleteonepost');
    }

    public static function routesUsers()
    {
        self::$router->map('POST', '/users/login', function () {
            $pagePosts = new Ctrl\User(self::$logger, self::$settings);
            $pagePosts->login();
        }, 'login');

        self::$router->map('POST', '/users/signup', function () {
            $pagePosts = new Ctrl\User(self::$logger, self::$settings);
            $pagePosts->signup();
        }, 'signup');
    }

    public static function routesComments()
    {
        self::$router->map('POST', '/posts/post/[:postId]/comments', function ($postId) {
            $pageComment = new Ctrl\Comment(self::$logger, self::$settings);
            $pageComment->createComment($postId);
        }, 'createcomment');

        self::$router->map('GET', '/posts/post/[:postId]/comments', function ($postId) {
            $pageComment = new Ctrl\Comment(self::$logger, self::$settings);
            $pageComment->selectAllComments($postId);
        }, 'selectallcomments');

        self::$router->map('PUT', '/posts/post/comments/comment/[:commentId]', function ($commentId) {
            $pageComment = new Ctrl\Comment(self::$logger, self::$settings);
            $pageComment->approveComment($commentId);
        }, 'approvecomment');

        self::$router->map('GET', '/posts/post/comments/toapprove', function () {
            $pageComment = new Ctrl\Comment(self::$logger, self::$settings);
            $pageComment->selectCommentsToApprove();
        }, 'selectcommentstoapprove');

        self::$router->map('DELETE', '/posts/post/comments/comment/[:commentId]', function ($commentId) {
            $pageComment = new Ctrl\Comment(self::$logger, self::$settings);
            $pageComment->refuseComment($commentId);
        }, 'refusecomment');
    }



}