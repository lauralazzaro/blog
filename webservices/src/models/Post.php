<?php

namespace LL\WS\models;

class Post extends Base
{
    const SELECT_ALL_POSTS = <<< SQL
        SELECT posts.id,
               posts.title,
               posts.teaser,
               posts.content,
               posts.created_at,
               posts.updated_at,
               user.username
        FROM posts
        INNER JOIN users user on posts.users_id = user.id
        WHERE deleted_at IS NULL
        order by posts.id DESC
SQL;

    const SELECT_ONE_POST = <<< SQL
        SELECT posts.id,
               posts.users_id,
               posts.title,
               posts.teaser,
               posts.content,
               posts.created_at,
               posts.updated_at,
               user.username
        FROM posts
        INNER JOIN users user on posts.users_id = user.id
        WHERE 
              posts.id = :idpost 
          AND 
              deleted_at IS NULL
SQL;

    const INSERT_POST = <<< SQL
        INSERT INTO posts 
            (
             users_id,
             title,
             teaser,
             content
            )
        VALUES 
            (
             :userid,
             :title,
             :teaser,
             :content
            )
SQL;

    const UPDATE_POST = <<< SQL
        UPDATE posts 
        SET
            title = :title,
            teaser = :teaser,
            content = :content
        WHERE 
            id = :idpost
          AND 
            deleted_at IS NULL
SQL;

    const DELETE_POST = <<< SQL
        UPDATE posts 
        SET
            deleted_at = CURRENT_TIMESTAMP     
        WHERE 
            id = :idpost
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
        $sql->bindValue(':teaser', $post->getTeaser());
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
        $sql->bindValue(':teaser', $arrPost['teaser']);
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