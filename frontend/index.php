<?php
require_once __DIR__ . '/vendor/autoload.php';

use Renderer\Renderer;

parse_str($_SERVER["QUERY_STRING"], $query_array);

if(!isset($query_array['page'])){
    $query_array['page'] ='';
}

$twigRenderer = new Renderer();

switch ($query_array['page']) {
    case '':
    case 'home':
        $twigRenderer->home();
        break;
    case 'posts':
        $twigRenderer->posts();
        break;
    case 'post':
        $id = $query_array['postid'];
        $twigRenderer->post($id);
        break;
    default:
        echo('page not found');
}
