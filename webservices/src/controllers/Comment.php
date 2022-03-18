<?php
namespace LL\WS\controllers;

use LL\WS\Models as mdl;
use LL\WS\classes as cls;

class Comment extends Base
{
    private mdl\Comment $modelComment;
    private User $ctrlUser;

    public function __construct($logger, $config)
    {
        parent::__construct($logger, $config);

        $this->modelComment = new \LL\WS\models\Comment($this->logger);
        $this->ctrlUser = new User($this->logger, $config);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function createComment($postId)
    {
        $this->logger->info('create comment');

        $body = $this->getBodyRequest();

        $userId = $this->validateToken($body->token);

        $this->ctrlUser->isAdmin($userId);

        $comment = new cls\Comment();
        $comment->setPostId($postId);
        $comment->setContent($body->content);
        $comment->setUserId($body->userid);

        $this->modelComment->insertComment($comment);

        echo true;
    }

    /**
     * @param $commentId
     * @return void
     * @throws \Exception
     */
    public function approveComment($commentId)
    {
        $this->logger->info('approve comment');

        $body = $this->getBodyRequest();

        $userId = $this->validateToken($body->token);

        $this->ctrlUser->isAdmin($userId);

        $this->modelComment->approveComment($commentId);

        echo true;
    }

    /**
     * @param $postId
     * @return void
     * @throws \Exception
     */
    public function selectAllComments($postId)
    {
        $this->logger->info('select comments');

        $dbArray = $this->modelComment->selectAllComments($postId);

        echo json_encode($dbArray);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function selectCommentsToApprove()
    {
        $this->logger->info('select comments to approve');
        $body = $this->getBodyRequest();

        $userId = $this->validateToken($body->token);

        $this->ctrlUser->isAdmin($userId);

        $dbArray = $this->modelComment->selectCommentsToApprove();

        echo json_encode($dbArray);
    }

    /**
     * @param $commentId
     * @return void
     * @throws \Exception
     */
    public function refuseComment($commentId)
    {
        $this->logger->info('select comments to approve');

        $body = $this->getBodyRequest();

        $userId = $this->validateToken($body->token);

        $this->ctrlUser->isAdmin($userId);

        $dbArray = $this->modelComment->refuseComment($commentId);

        echo json_encode($dbArray);
    }
}