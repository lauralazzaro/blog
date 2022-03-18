<?php
namespace Toolbox;

class Functions
{
    public function getPosts()
    {

        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/posts/posts';

        $content = $this->curlGet($url, []);

        return ($content);
    }

    public function getOnePost($postId)
    {

        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/posts/post/' . $postId;

        $content = $this->curlGet($url, []);

        return ($content);
    }

    public function login($form)
    {
        $form = json_encode($form);

        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/users/login';

        $content = $this->curlForm($url, $form);

        $_SESSION['role'] = $content['role'];
        $_SESSION['iduser'] = $content['id'];
        $_SESSION['token'] = $content['token'];
        $_SESSION['connected'] = true;

        header('location: ?page=home');
    }

    public function signup($form)
    {

        $form = json_encode($form);

        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/users/signup';

        $content = $this->curlForm($url, $form);

        $_SESSION['role'] = $content['role'];
        $_SESSION['iduser'] = $content['id'];
        $_SESSION['token'] = $content['token'];
        $_SESSION['connected'] = true;

        header('location: ?page=login');
    }

    public function getCommentsForPost($postId)
    {

        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/posts/post/' . $postId .'/comments';

        $content = $this->curlGet($url, []);

        return ($content);
    }

    public function getCommentsToApprove()
    {
        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/posts/post/comments/toapprove';

        $body = [
            'token' => $_SESSION['token']
            ];
        $content = $this->curlGet($url, $body);

        return ($content);
    }

    public function approveComment($commentId)
    {
        $url = "http://localhost/bloglauralazzaro/webservices/api/v1/posts/post/comments/comment/$commentId";

        $this->curlPut($url, ['token' => $_SESSION['token']]);

        header('location: ?page=adminpage');
    }

    public function deleteComment($commentId)
    {
        $url = "http://localhost/bloglauralazzaro/webservices/api/v1/posts/post/comments/comment/$commentId";

        $this->curlDelete($url, []);

        header('location: ?page=adminpage');

    }

    public function addComment($comment)
    {
        $postId = $comment['postid'];

        unset($comment['postid']);

        $comment = json_encode($comment);

        $url = "http://localhost/bloglauralazzaro/webservices/api/v1/posts/post/$postId/comments";

        $this->curlForm($url, $comment);

        header("location: ?page=post&postid=$postId");

    }

    private function curlGet($url, $data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'GET' );
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($curl);
        curl_close($curl);

        $content = json_decode($response, true);

        return $content;
    }

    private function curlForm($url, $body)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_POSTFIELDS,     $body);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,     array('Content-Type: application/json'));
        $response = curl_exec($curl);

        curl_close($curl);

        $content = json_decode($response, true);

        return $content;
    }

    private function curlPut($url, $data)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS,http_build_query($data));
        $response = curl_exec($curl);

        curl_close($curl);

        $content = json_decode($response, true);

        return $content;
    }

    private function curlDelete($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        $response = curl_exec($curl);

        curl_close($curl);

        $content = json_decode($response, true);

        return $content;
    }
}