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
        $twigRenderer->home();
        break;
    case 'posts':
        $twigRenderer->posts();
        break;
    case 'post':
        $postId = $query_array['postid'];
        $twigRenderer->post($postId);
        break;
    case 'login':
        $twigRenderer->login();
        break;
    case 'sendlogin':
        $loginForm = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $res = $function->login($loginForm);
        if($res === 'loginfail') {
            $twigRenderer->login($res);
        }
        break;
    case 'sendsignup':
        $signForm = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $function->signup($signForm);
        $twigRenderer->login();
        break;
    case 'signup':
        $twigRenderer->signup();
        break;
    case 'logout':
        $function->logout();
        $session->unsetSession();
        break;
    case 'admin':
        if ($session->getSession('role') === 'admin') {
            $twigRenderer->adminPage();
        }
        $twigRenderer->home('', 'true');
        break;
    case 'approvecomment':
        if ($session->getSession('role') === 'admin') {
            $commentId = $query_array['commentid'];
            $function->approveComment($commentId);
            $twigRenderer->adminPage();
        }
        $twigRenderer->home('', 'true');
        break;
    case 'deletecomment':
        if ($session->getSession('role') === 'admin') {
            $commentId = $query_array['commentid'];
            $function->deleteComment($commentId);
            $twigRenderer->adminPage();
        }
        $twigRenderer->home('', 'true');
        break;
    case 'addcomment':
        if ($session->getSession('role') === 'user' || $session->getSession('role') === 'admin') {
            $comment = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $resCommentSent = $function->addComment($comment);
            $twigRenderer->post($comment['postid'], $resCommentSent);
        }
        $twigRenderer->home('', 'true');
        break;
    case 'deletepost':
        if ($session->getSession('role') === 'admin') {
            $postId = $query_array['postid'];
            $function->deletePost($postId);
        }
        $twigRenderer->home('', 'true');
        break;
    case 'updatepost':
        if ($session->getSession('role') === 'admin') {
            $postId = $query_array['postid'];
            $twigRenderer->updatePost($postId);
        }
        $twigRenderer->home('', 'true');
        break;
    case 'sendupdatedpost':
        if ($session->getSession('role') === 'admin') {
            $postId = $query_array['postid'];
            $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $function->updatePost($postId, $form);
            $twigRenderer->updatePost($postId);
        }
        $twigRenderer->home('', 'true');
        break;
    case 'createpost':
        if ($session->getSession('role') === 'admin') {
            $twigRenderer->createPost();
        }
        $twigRenderer->home('', 'true');
        break;
    case 'sendcreatepost':
        if ($session->getSession('role') === 'admin') {
            $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $function->createPost($form);
            $twigRenderer->posts();
        }
        $twigRenderer->home('', 'true');
        break;
    case 'contact':
        $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $resSendEmail = $function->sendEmail($form);
        $twigRenderer->home($resSendEmail, '');
        break;
    default:
        echo('page not found');
}
