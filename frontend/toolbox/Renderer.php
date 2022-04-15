<?php

namespace Toolbox;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;


class Renderer
{
    private Environment $twig;
    private Functions $functions;
    private Session $session;
    private array $settings;
    private string $role;

    public function __construct($settings)
    {
        $loader = new FilesystemLoader('./templates');
        $this->twig = new Environment($loader, [
            //    'cache' => __DIR__ . '/tmp'
            'cache' => false
        ]);

        $this->twig->addGlobal('session', $_SESSION);

        $this->settings = $settings;
        $this->session = new Session();
        $this->functions = new Functions($this->settings);
        $this->checkRole();
    }

    public function home($resSendEmail = '', $forbidden = '')
    {
        echo $this->twig->render('index.twig',
            [
                'title' => 'Laura Lazzaro',
                'teaser' => 'Super php developer',
                'role' => $this->role,
                'emailSent' => $resSendEmail,
                'forbidden' => $forbidden
            ]);
    }

    public function posts()
    {
        $posts = $this->functions->getPosts();
        echo $this->twig->render('posts.twig', ['posts' => $posts, 'role' => $this->role]);
    }

    public function post($postid, $commentSent = '')
    {
        $post = $this->functions->getOnePost($postid);
        $comments = $this->functions->getCommentsForPost($postid);

        echo $this->twig->render('post.twig',
            [
                'post' => $post,
                'comments' => $comments,
                'role' => $this->role,
                'connected' => $this->session->getSession('connected'),
                'commentSent' => $commentSent
            ]);
    }

    public function updatePost($postid)
    {
        $post = $this->functions->getOnePost($postid);

        echo $this->twig->render('updatepost.twig',
            [
                'post' => $post,
                'role' => $this->role
            ]);
    }

    public function login($res = '')
    {
        echo $this->twig->render('login.twig', [
            'loginresult' => $res
        ]);
    }

    public function signup()
    {
        echo $this->twig->render('signup.twig');
    }

    public function createPost()
    {
        echo $this->twig->render('createpost.twig');
    }

    public function adminPage()
    {
        $comments = $this->functions->getCommentsToApprove();
        echo $this->twig->render('adminpage.twig',
            [
                'comments' => $comments,
                'role' => $this->role
            ]);
    }

    private function checkRole()
    {
        $this->role = '';
        if($this->session->getSession('role')) {
            $this->role = $this->session->getSession('role');
        }
    }
}