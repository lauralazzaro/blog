<?php
require_once __DIR__ . '/vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader, [
//    'cache' => __DIR__ . '/tmp'
    'cache' => false
]);

$page = '';

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

switch ($page) {

    case '':
    case 'home':
        echo $twig->render('index.twig');
        break;
    case 'posts':
        echo $twig->render('posts.twig');

    default:
        echo('page not found');
}
