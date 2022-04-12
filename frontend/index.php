<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';


use Toolbox\Functions;
use Toolbox\Renderer;
use Toolbox\Session;

parse_str(filter_input(INPUT_SERVER, 'QUERY_STRING', FILTER_SANITIZE_STRING), $query_array);

if (!isset($query_array['page'])) {
    $query_array['page'] = '';
}

$setting = require_once '../_settings/settings.php';
$twigRenderer = new Renderer($setting);
$function = new Functions($setting);
$session = new Session();

switch ($query_array['page']) {
    case '':
    case 'home':
        $twigRenderer->home('');
        break;
    case 'posts':
        $twigRenderer->posts();
        break;
    case 'post':
        $postId = $query_array['postid'];
        $twigRenderer->post($postId, '');
        break;
    case 'login':
        $twigRenderer->login();
        break;
    case 'sendlogin':
        $loginForm = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $function->login($loginForm);
        break;
    case 'sendsignup':
        $signForm = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $function->signup($signForm);
        break;
    case 'signup':
        $twigRenderer->signup();
        break;
    case 'logout':
        $function->logout();
        $session->unsetSession();
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
        $comment = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $resCommentSent = $function->addComment($comment);
        $twigRenderer->post($comment['postid'], $resCommentSent);
        break;
    case 'deletepost':
        $postId = $query_array['postid'];
        $function->deletePost($postId);
        break;
    case 'updatepost':
        $postId = $query_array['postid'];
        $twigRenderer->updatePost($postId);
        break;
    case 'sendupdatedpost':
        $postId = $query_array['postid'];
        $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $function->updatePost($postId, $form);
        break;
    case 'createpost':
        $twigRenderer->createPost();
        break;
    case 'sendcreatepost':
        $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $function->createPost($form);
        $twigRenderer->posts();
        break;
    case 'contact':
        $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $resSendEmail = $function->sendEmail($form);
        $twigRenderer->home($resSendEmail);
        break;
    default:
        echo('page not found');
}
