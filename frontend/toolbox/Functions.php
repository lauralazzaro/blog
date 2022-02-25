<?php
namespace Toolbox;

class Functions
{
    public function getPosts()
    {

        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/posts/posts';

        $content = $this->curlPost($url);

        return ($content);
    }

    public function getOnePost($id)
    {

        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/posts/post/' . $id;

        $content = $this->curlPost($url);

        return ($content);
    }


    private function curlPost($url)
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
}