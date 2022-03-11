<?php
namespace Toolbox;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;


class Renderer
{
    private $twig;
    private $functions;
    private $role;

    public function __construct()
    {
        $loader = new FilesystemLoader('./templates');
        $this->twig = new Environment($loader, [
            //    'cache' => __DIR__ . '/tmp'
            'cache' => false
        ]);

        $this->twig->addGlobal('session', $_SESSION);

        $this->functions = new Functions();
        $this->checkRole();

    }

    public function home()
    {
        echo $this->twig->render('index.twig', ['title' => 'Laura Lazzaro', 'teaser' => 'Super php developer', 'role' => $this->role]);
    }

    public function posts()
    {
        $posts = $this->functions->getPosts();

        echo $this->twig->render('posts.twig', ['posts' => $posts, 'role' => $this->role]);
    }

    public function post($postid)
    {
        $post = $this->functions->getOnePost($postid);
        $comments = $this->functions->getCommentsForPost($postid);

        echo $this->twig->render('post.twig', ['post' => $post, 'comments' => $comments, 'role' => $this->role]);
    }

    public function login()
    {
        echo $this->twig->render('login.twig');
    }

    public function signup()
    {
        echo $this->twig->render('signup.twig');
    }

    public function adminPage()
    {
        if($this->role === 'admin') {
            $comments = $this->functions->getCommentsToApprove();
            echo $this->twig->render('adminpage.twig', ['comments' => $comments, 'role' => $this->role]);
        } else {
            header('location: ?page=home');
        }
    }

    private function checkRole()
    {
        if(filter_var($_SESSION['role'])){
            $this->role = filter_var($_SESSION['role']);
        } else {
            $this->role = '';
        }
    }
}