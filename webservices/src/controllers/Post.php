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

        $output = json_encode($dbArray);

        echo $output;
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function getAllPost()
    {
        $this->logger->info('get all posts');

        $dbArray = $this->modelPost->selectAllPosts();

        $output = json_encode($dbArray);

        echo $output;

    }

    /**
     * @param $id
     * @return void
     * @throws \Exception
     */
    public function getOnePost($id)
    {
        $this->logger->info('get one post');

        $dbArray = $this->modelPost->selectOnePost($id);

        $output = json_encode($dbArray);

        echo $output;
    }

    public function updateOnePost($id)
    {
        echo 'update one post';
    }

    public function deleteOnePost($id)
    {
        echo 'delete one post';
    }



}