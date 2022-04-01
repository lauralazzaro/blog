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

    const GET_USER_FROM_TOKEN = <<< SQL
        SELECT role
        FROM users
        WHERE token = :token
SQL;

    const TOKEN_LOGIN = <<< SQL
        UPDATE users
        SET token = :token
        WHERE id = :id
SQL;

    const TOKEN_LOGOUT = <<< SQL
        UPDATE users
        SET token = null
        WHERE id = :id
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
            throw new \Exception('500.Error while inserting new row');
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
            throw new \Exception('401.Email or password error');
        }
        return $row;
    }

    /**
     * @param \LL\WS\classes\User $user
     * @return bool
     * @throws \Exception
     */
    public function createTokenLogin($userId, $token): bool
    {
        $sql = $this->dbConnection->prepare(self::TOKEN_LOGIN);

        $sql->bindValue(':id', $userId);
        $sql->bindValue(':token', $token);

        $sql->execute();

        $updateSucceded = $sql->rowCount();

        if ($updateSucceded === 0) {
            throw new \Exception('401.User not found');
        }
        return true;
    }

    /**
     * @param \LL\WS\classes\User $user
     * @return bool
     * @throws \Exception
     */
    public function deleteTokenLogin($userId): bool
    {
        $sql = $this->dbConnection->prepare(self::TOKEN_LOGOUT);

        $sql->bindValue(':id', $userId);

        $sql->execute();

        $updateSucceded = $sql->rowCount();

        if ($updateSucceded === 0) {
            throw new \Exception('401.User not found');
        }
        return true;
    }

    /**
     * @param $token
     * @return array
     * @throws \Exception
     */
    public function getUserFromToken($token): array
    {
        $sql = $this->dbConnection->prepare(self::GET_USER_FROM_TOKEN);

        $sql->bindValue(':token', $token);

        $sql->execute();

        $row = $sql->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            throw new \Exception('401.User not found');
        }
        return $row;
    }
}