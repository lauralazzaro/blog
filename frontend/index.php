<?php
require_once __DIR__ . '/vendor/autoload.php';

use Renderer\Renderer;


$page = '';

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

$twigRenderer = new Renderer();

switch ($page) {
    case '':
    case 'home':
        $twigRenderer->home();
        break;
    case 'posts':
        $twigRenderer->posts();
        break;
    default:
        echo('page not found');
}
