<?php

namespace LL\WS\models;

class Post extends Base
{
    const SELECT_ALL_POSTS = <<< SQL
        SELECT pst_pk as postid,
               pst_usr_pk as userid,
               pst_title as title,
               pst_lead as leadpst,
               pst_content as content,
               pst_created_at as created_at
        FROM posts
        WHERE pst_is_deleted = 0
SQL;

    const SELECT_ONE_POST = <<< SQL
        SELECT pst_pk as postid,
               pst_usr_pk as userid,
               pst_title as title,
               pst_lead as leadpst,
               pst_content as content,
               pst_created_at as created_at
        FROM posts
        WHERE 
              pst_pk = :idpost 
          AND 
              pst_is_deleted = 0
SQL;

    const INSERT_POST = <<< SQL
        INSERT INTO posts 
            (
             pst_usr_pk,
             pst_title,
             pst_lead,
             pst_content
            )
        VALUES 
            (
             :userid,
             :title,
             :leadpst,
             :content
            )
SQL;

    const UPDATE_POST = <<< SQL
        UPDATE posts 
        SET
            pst_title = :title,
            pst_lead = :leadpst,
            pst_content = :content
        WHERE 
            pst_pk = :idpost
          AND 
            pst_is_deleted = 0
SQL;

    const DELETE_POST = <<< SQL
        UPDATE posts 
        SET
            pst_is_deleted = 1     
        WHERE 
            pst_pk = :idpost
SQL;
    /**
     * @return array
     * @throws \Exception
     */
    public function selectAllPosts(): array
    {
        $sql = $this->dbConnection->prepare(self::SELECT_ALL_POSTS);
        $sql->execute();

        $rows = $sql->fetchAll(\PDO::FETCH_ASSOC);

        if (!$rows) {
            throw new \Exception('No posts found');
        }

        return $rows;
    }

    /**
     * @param int $id
     *
     * @return array
     * @throws \Exception
     */
    public function selectOnePost(int $idPost): array
    {
        $sql = $this->dbConnection->prepare(self::SELECT_ONE_POST);
        $sql->bindValue(':idpost', $idPost);

        $sql->execute();

        $row = $sql->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            throw new \Exception('No post found');
        }
        return $row;
    }

    /**
     * @param \LL\WS\classes\Post $post
     * @return int
     * @throws \Exception
     */
    public function insertPost(\LL\WS\classes\Post $post): int
    {
        $sql = $this->dbConnection->prepare(self::INSERT_POST);

        $sql->bindValue(':userid', $post->getIdAuthor());
        $sql->bindValue(':title', $post->getTitle());
        $sql->bindValue(':leadpst', $post->getLead());
        $sql->bindValue(':content', $post->getContent());

        $sql->execute();

        if (!$this->dbConnection->lastInsertId()) {
            throw new \Exception('Error while inserting new row');
        }

        return (int)$this->dbConnection->lastInsertId();
    }

    public function updateOnePost(array $arrPost): bool
    {
        $sql = $this->dbConnection->prepare(self::UPDATE_POST);
        $sql->bindValue(':idpost', $arrPost['idpost']);
        $sql->bindValue(':title', $arrPost['title']);
        $sql->bindValue(':leadpst', $arrPost['leadpst']);
        $sql->bindValue(':content', $arrPost['content']);

        $sql->execute();

        $updateSucceded = $sql->rowCount();

        if ($updateSucceded === 0) {
            throw new \Exception('No post updated');
        }
        return true;
    }

    public function deleteOnePost(int $idPost): int
    {
        $sql = $this->dbConnection->prepare(self::DELETE_POST);
        $sql->bindValue(':idpost', $idPost);

        $sql->execute();

        $updateSucceded = $sql->rowCount();

        if ($updateSucceded === 0) {
            throw new \Exception('No post deleted');
        }
        return true;
    }
}