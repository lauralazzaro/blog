<?php

namespace LL\WS\controllers;

class Comment
{
    public function createComment()
    {
        echo 'create one comment';
    }

    public function getAllComment()
    {
        echo 'get all comment';
    }

    public function approveOneComment($id)
    {
        echo 'update one comment';
    }

    public function deleteOneComment($id)
    {
        echo 'delete one comment';
    }
}