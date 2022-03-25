<?php

namespace Toolbox;

class Functions
{
    private Session $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function getPosts()
    {

        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/posts/posts';

        $content = $this->curl($url, [], 'GET');

        return ($content);
    }

    public function getOnePost($postId)
    {

        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/posts/post/' . $postId;

        $content = $this->curl($url, [], 'GET');

        return ($content);
    }

    public function login($form)
    {
        $form = json_encode($form);

        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/users/login';

        $content = $this->curlForm($url, $form);

        $this->session->setSession('role', $content['role']);
        $this->session->setSession('iduser', $content['id']);
        $this->session->setSession('token', $content['token']);
        $this->session->setSession('connected', true);

        header('location: ?page=home');
    }

    public function signup($form)
    {

        $form = json_encode($form);

        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/users/signup';

        $this->curlForm($url, $form);

        header('location: ?page=login');
    }

    public function getCommentsForPost($postId)
    {

        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/posts/post/' . $postId . '/comments';

        $content = $this->curl($url, [], 'GET');

        return ($content);
    }

    public function getCommentsToApprove()
    {
        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/posts/post/comments/toapprove';
        $body = [
            'token' => $this->session->getSession('token')
        ];

        $content = $this->curl($url, $body, 'GET');

        return ($content);
    }

    public function approveComment($commentId)
    {
        $url = "http://localhost/bloglauralazzaro/webservices/api/v1/posts/post/comments/comment/$commentId";
        $body = [
            'token' => $this->session->getSession('token')
        ];

        $this->curl($url, $body, 'PUT');

        header('location: ?page=adminpage');
    }

    public function deleteComment($commentId)
    {
        $url = "http://localhost/bloglauralazzaro/webservices/api/v1/posts/post/comments/comment/$commentId";
        $body = [
            'token' => $this->session->getSession('token')
        ];

        $this->curl($url, $body, 'DELETE');

        header('location: ?page=adminpage');
    }

    public function addComment($comment)
    {
        $postId = $comment['postid'];
        unset($comment['postid']);

        $comment['token'] = $this->session->getSession('token');
        $comment = json_encode($comment);

        $url = "http://localhost/bloglauralazzaro/webservices/api/v1/posts/post/$postId/comments";

        $this->curlForm($url, $comment);

        header("location: ?page=post&postid=$postId");
    }

    public function deletePost($postId)
    {
        $url = "http://localhost/bloglauralazzaro/webservices/api/v1/posts/post/$postId/delete";
        $body = [
            'token' => $this->session->getSession('token')
        ];

        $this->curl($url, $body, 'DELETE');

        header('location: ?page=posts');
    }

    private function curlForm($url, $body)
    {
        $curl = curl_init();

        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => FALSE,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json')
        );

        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    private function curl($url, $data, $method)
    {
        $curl = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HEADER => FALSE,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json')
        );

        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }
}