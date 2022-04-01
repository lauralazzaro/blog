<?php

namespace LL\WS\controllers;

use LL\WS\Models as mdl;
use LL\WS\classes as cls;

class Post extends Base
{

    private mdl\Post $modelPost;
    private User $ctrlUser;

    public function __construct($logger, $config)
    {
        parent::__construct($logger, $config);

        $this->modelPost = new \LL\WS\models\Post($this->logger);
        $this->ctrlUser = new User($this->logger, $config);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function createPost()
    {
        $this->logger->info('create post');

        $body = $this->getBodyRequest();

        $user = $this->ctrlUser->getUserFromToken($body->token);

        if($user['role'] !== 'admin'){
            throw new \Exception('403.Access Forbidden');
        }

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
        $this->logger->info('update one post');

        $arrPost = $this->getBodyRequest(true);

        $user = $this->ctrlUser->getUserFromToken($arrPost['token']);

        if($user['role'] !== 'admin'){
            throw new \Exception('403.Access Forbidden');
        }

        $arrPost['idpost'] = $idPost;

        $this->modelPost->updateOnePost($arrPost);

        $dbArray = $this->modelPost->selectOnePost($idPost);

        echo json_encode($dbArray);
    }

    public function deleteOnePost($idPost)
    {
        $this->logger->info('delete one post');

        $body = $this->getBodyRequest();

        $user = $this->ctrlUser->getUserFromToken($body->token);

        if($user['role'] !== 'admin'){
            throw new \Exception('403.Access Forbidden');
        }

        $this->modelPost->deleteOnePost($idPost);

        echo json_encode(true);
    }



}