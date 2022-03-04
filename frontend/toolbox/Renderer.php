<?php
namespace Toolbox;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;


class Renderer
{
    private $twig;
    private $functions;

    public function __construct()
    {
        $loader = new FilesystemLoader('./templates');
        $this->twig = new Environment($loader, [
            //    'cache' => __DIR__ . '/tmp'
            'cache' => false
        ]);

        $this->twig->addGlobal('session', $_SESSION);

        $this->functions = new Functions();
    }

    public function home()
    {
        echo $this->twig->render('index.twig', ['title' => 'Laura Lazzaro', 'teaser' => 'Super php developer']);
    }

    public function posts()
    {
        $posts = $this->functions->getPosts();

        echo $this->twig->render('posts.twig', ['posts' => $posts]);
    }

    public function post($id)
    {
        $post = $this->functions->getOnePost($id);

        echo $this->twig->render('post.twig', ['post' => $post]);
    }

    public function login()
    {
        echo $this->twig->render('login.twig');
    }

    public function signup()
    {
        echo $this->twig->render('signup.twig');
    }
}