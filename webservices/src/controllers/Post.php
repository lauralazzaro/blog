<?php

namespace LL\WS\controllers;

use LL\WS\Models as mdl;
use LL\WS\classes as cls;

class Post extends Base
{

    private mdl\Post $modelPost;

    public function __construct($logger, $config)
    {
        parent::__construct($logger, $config);

        $this->modelPost = new \LL\WS\models\Post($this->logger);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function createPost()
    {
        $this->logger->info('create post');

        $body = $this->getBodyRequest();

        $post = new cls\Post();

        $post->setIdAuthor($body->userid);
        $post->setTitle($body->title);
        $post->setLead($body->leadpst);
        $post->setContent($body->content);

        $idNewPost = $this->modelPost->insertPost($post);

        $dbArray = $this->modelPost->selectOnePost($idNewPost);

        echo json_encode($dbArray);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function getAllPost()
    {
        $this->logger->info('get all posts');

        $dbArray = $this->modelPost->selectAllPosts();

        echo json_encode($dbArray);
    }

    /**
     * @param $idPost
     * @return void
     * @throws \Exception
     */
    public function getOnePost($idPost)
    {
        $this->logger->info('get one post');

        $dbArray = $this->modelPost->selectOnePost($idPost);

        echo json_encode($dbArray);
    }

    public function updateOnePost($idPost)
    {
    }

    public function deleteOnePost($idPost)
    {
    }



}