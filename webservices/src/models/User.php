<?php

namespace LL\WS\models;

class User extends Base
{
    const INSERT_USER = <<< SQL
        INSERT INTO users (email, password, username)
        VALUES (:email, :password, :username)
SQL;

    const SELECT_USER = <<< SQL
        SELECT *
        FROM users
        WHERE email = :email AND password = :password
SQL;

    /**
     * @param \LL\WS\classes\User $user
     * @return int
     * @throws \Exception
     */
    public function insertUser(\LL\WS\classes\User $user): int
    {
        $sql = $this->dbConnection->prepare(self::INSERT_USER);

        $sql->bindValue(':email', $user->getEmail());
        $sql->bindValue(':password', $user->getPassword());
        $sql->bindValue(':username', $user->getUsername());

        $sql->execute();

        if (!$this->dbConnection->lastInsertId()) {
            throw new \Exception('Error while inserting new row');
        }

        return (int)$this->dbConnection->lastInsertId();
    }

    /**
     * @param \LL\WS\classes\User $user
     * @return array
     * @throws \Exception
     */
    public function selectUser(\LL\WS\classes\User $user): array
    {
        $sql = $this->dbConnection->prepare(self::SELECT_USER);

        $sql->bindValue(':email', $user->getEmail());
        $sql->bindValue(':password', $user->getPassword());

        $sql->execute();

        $row = $sql->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            throw new \Exception('Email or password error');
        }
        return $row;
    }
}