<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';


use Toolbox\Functions;
use Toolbox\Renderer;

parse_str($_SERVER["QUERY_STRING"], $query_array);

if (!isset($query_array['page'])) {
    $query_array['page'] = '';
}

$twigRenderer = new Renderer();
$function = new Functions();

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
    case 'login':
        $twigRenderer->login();
        break;
    case 'sendlogin':
        $post = $_POST;
        $function->login($post);
        break;
    case 'sendsignup':
        $post = $_POST;
        $function->signup($post);
        break;
    case 'signup':
        $post = $_POST;
        $twigRenderer->signup();
        break;
    case 'logout':
        $_SESSION['connected'] = false;
        $_SESSION['role'] = '';
        header('location: ?page=home');
        break;
    case 'admin':
        $twigRenderer->adminPage();
        break;
    case 'approvecomment':
        $commentId = $query_array['commentid'];
        $function->approveComment($commentId);
        header('location: ?page=admin');
        break;
    case 'deletecomment':
        $commentId = $query_array['commentid'];
        $function->deleteComment($commentId);
        header('location: ?page=admin');
        break;
    case 'addcomment':
        $comment = $_POST;
        $function->addComment($comment);
        break;
    default:
        echo('page not found');
}
