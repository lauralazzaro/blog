<?php

namespace Toolbox;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Functions
{
    private Session $session;
    private array $settings;

    public function __construct($settings)
    {
        $this->session = new Session();
        $this->settings = $settings;
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

    public function updatePost($postId, $form)
    {
        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/posts/post/' . $postId . '/update';
        $form['token'] = $this->session->getSession('token');
        $form['idpost'] = $postId;

        $body = json_encode($form);
        $this->curlForm($url, $body, 'PUT');
    }

    public function login($form)
    {
        $form = json_encode($form);

        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/users/login';

        $content = $this->curlForm($url, $form, 'POST');

        if($content){
            $this->session->setSession('role', $content['role']);
            $this->session->setSession('iduser', $content['id']);
            $this->session->setSession('username', $content['username']);
            $this->session->setSession('token', $content['token']);
            $this->session->setSession('connected', true);

            header('location: ?page=home');
        }

        return 'loginfail';
    }

    public function signup($form)
    {

        $form = json_encode($form);

        $url = 'http://localhost/bloglauralazzaro/webservices/api/v1/users/signup';

        $this->curlForm($url, $form, 'POST');
    }

    public function logout()
    {

        $userId = $this->session->getSession('iduser');

        $url = "http://localhost/bloglauralazzaro/webservices/api/v1/users/user/$userId/logout";

        $this->curl($url, [], 'DELETE');

        header('location: ?page=home');
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
    }

    public function deleteComment($commentId)
    {
        $url = "http://localhost/bloglauralazzaro/webservices/api/v1/posts/post/comments/comment/$commentId";
        $body = [
            'token' => $this->session->getSession('token')
        ];

        $this->curl($url, $body, 'DELETE');
    }

    public function addComment($comment)
    {
        $postId = $comment['postid'];
        unset($comment['postid']);

        $comment['token'] = $this->session->getSession('token');
        $comment = json_encode($comment);

        $url = "http://localhost/bloglauralazzaro/webservices/api/v1/posts/post/$postId/comments";

        if ($this->curlForm($url, $comment, 'POST') === null) {
            return 'failed';
        }
        return 'success';
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

    public function sendEmail($form)
    {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $this->settings['email']['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $this->settings['email']['username'];
            $mail->Password = $this->settings['email']['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $this->settings['email']['port'];

            //Recipients
            $mail->setFrom($this->settings['email']['username'], 'Blog Laura Lazzaro');
            $mail->addAddress($form['email']);               //Name is optional

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = "Contact form from laura lazzaro's blog";
            $mail->Body = $form['name'] . ' sent this message: <br>' . $form['message'];

            $mail->send();

            $res = 'success';
        } catch (Exception $e) {
            $res = 'failed';
        }
        return $res;
    }

    public function createPost($form)
    {
        $url = "http://localhost/bloglauralazzaro/webservices/api/v1/posts/post";
        $form['token'] = $this->session->getSession('token');

        $body = json_encode($form);

        $this->curlForm($url, $body, 'POST');

        header('location: ?page=posts');
    }


    private function curlForm($url, $body, $method)
    {
        $curl = curl_init();

        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_CUSTOMREQUEST => $method,
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