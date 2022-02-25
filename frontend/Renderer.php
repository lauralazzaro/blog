<?php
namespace Renderer;

use Toolbox\Functions;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


class Renderer
{
    private $twig;
    private $functions;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/templates');
        $this->twig = new Environment($loader, [
            //    'cache' => __DIR__ . '/tmp'
            'cache' => false
        ]);

        $this->functions = new Functions();
    }

    public function home()
    {
        echo $this->twig->render('index.twig');
    }

    public function posts()
    {
        $posts = $this->functions->getPosts();
//        print_r($posts);

        echo $this->twig->render('posts.twig', ['posts' => $posts]);
    }
}