<?php

namespace LL\WS\models;

class Comment extends Base
{
    const SELECT_ALL_COMMENTS = <<< SQL
        SELECT comments.id,
               comments.content,
               comments.updated_at,
               user.username
        FROM comments
        INNER JOIN users user on comments.users_id = user.id
        WHERE comments.approved = 1 
        AND
              comments.posts_id = :postid
        ORDER BY comments.updated_at DESC
SQL;

    const APPROVE_COMMENT = <<< SQL
        UPDATE comments
        SET approved = 1
        WHERE id = :commentid 
SQL;

    const INSERT_COMMENT = <<< SQL
        INSERT INTO comments (users_id, posts_id, content)
        VALUES (:userid, :postid, :content)
SQL;

    const SELECT_COMMENTS_TO_APPROVE = <<< SQL
        SELECT comments.id,
               comments.content,
               comments.updated_at,
               comments.created_at,
               user.username,
               post.title 
        FROM comments
        INNER JOIN users user on comments.users_id = user.id
        INNER JOIN posts post on comments.posts_id = post.id
        WHERE comments.approved = 0
        AND
              comments.deleted_at is null
        order by comments.created_at DESC
SQL;

    const REFUSE_COMMENT = <<< SQL
        UPDATE comments
        SET deleted_at = current_timestamp
        WHERE id = :commentid 
SQL;


    /**
     * @param int $postId
     * @return array
     * @throws \Exception
     */
    public function selectAllComments(int $postId): array
    {
        $sql = $this->dbConnection->prepare(self::SELECT_ALL_COMMENTS);

        $sql->bindValue(':postid', $postId);

        $sql->execute();

        $rows = $sql->fetchAll(\PDO::FETCH_ASSOC);

        if (!$rows) {
            throw new \Exception('No comments found');
        }

        return $rows;
    }

    public function selectCommentsToApprove(): array
    {
        $sql = $this->dbConnection->prepare(self::SELECT_COMMENTS_TO_APPROVE);

        $sql->execute();

        $rows = $sql->fetchAll(\PDO::FETCH_ASSOC);

        if (!$rows) {
            throw new \Exception('No comments found');
        }

        return $rows;
    }

    /**
     * @param \LL\WS\classes\Comment $comment
     * @return bool
     * @throws \Exception
     */
    public function insertComment(\LL\WS\classes\Comment $comment): bool
    {
        $sql = $this->dbConnection->prepare(self::INSERT_COMMENT);

        $sql->bindValue(':userid', $comment->getUserId());
        $sql->bindValue(':postid', $comment->getPostId());
        $sql->bindValue(':content', $comment->getContent());

        $sql->execute();

        if (!$this->dbConnection->lastInsertId()) {
            throw new \Exception('Error while inserting new row');
        }

        return true;
    }

    /**
     * @param int $commentId
     * @return bool
     * @throws \Exception
     */
    public function approveComment(int $commentId): bool
    {
        $sql = $this->dbConnection->prepare(self::APPROVE_COMMENT);
        $sql->bindValue(':commentid', $commentId);

        $sql->execute();

        $updateSucceded = $sql->rowCount();

        if ($updateSucceded === 0) {
            throw new \Exception('No post updated');
        }

        return true;
    }

    /**
     * @param int $commentId
     * @return bool
     * @throws \Exception
     */
    public function refuseComment(int $commentId): bool
    {
        $sql = $this->dbConnection->prepare(self::REFUSE_COMMENT);
        $sql->bindValue(':commentid', $commentId);

        $sql->execute();

        $updateSucceded = $sql->rowCount();

        if ($updateSucceded === 0) {
            throw new \Exception('No post updated');
        }

        return true;
    }

}