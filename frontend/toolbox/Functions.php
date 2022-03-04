<?php
namespace Toolbox;

class Functions
{
    public function getPosts()
    {

        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/posts/posts';

        $content = $this->curlGet($url);

        return ($content);
    }

    public function getOnePost($id)
    {

        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/posts/post/' . $id;

        $content = $this->curlGet($url);

        return ($content);
    }

    public function login($form)
    {

        $form = json_encode($form);

        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/users/login';

        $content = $this->curlForm($url, $form);

        $_SESSION['role'] = $content['role'];
        $_SESSION['connected'] = true;

        header('location: ?page=home');
    }

    public function signup($form)
    {

        $form = json_encode($form);

        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/users/signup';

        $content = $this->curlForm($url, $form);

        $_SESSION['role'] = $content['role'];
        $_SESSION['connected'] = true;

        header('location: ?page=login');
    }

    public function getCommentsForPost($id)
    {

        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/posts/post/' . $id .'/comments';

        $content = $this->curlGet($url);

        return ($content);
    }

    private function curlGet($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);

        curl_close($ch);

        $content = json_decode($response, true);

        return $content;
    }

    private function curlForm($url, $body)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS,     $body);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: application/json'));
        $response = curl_exec($ch);

        curl_close($ch);

        $content = json_decode($response, true);

        return $content;
    }
}