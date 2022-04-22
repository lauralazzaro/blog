<?php
return [
    'webservices' => [
        'basepath' => '/public/'
    ],
    'db' => [
        'dbuser' => '',
        'dbpassword' => '',
        'dbhost' => 'localhost',
        'dbport' => '3306',
        'dbname' => 'blog_lauralazzaro'
    ],
    'token' => [
        'secret' => 'sec!ReT423*&',
        'expiration' => time() + 3600,
        'issuer' => ''
    ],
    'email' => [
        'host' => '',
        'port' => 587,
        'username' => '',
        'password' => '',
    ]
];