<?php
use LL\WS\Routes\Routes;

require './vendor/autoload.php';


$settings = '../_settings/settings.php';

if(!file_exists($settings)){
    exit('Please complete the installation');
}

Routes::$settings = require_once '../_settings/settings.php';
Routes::init();
Routes::route();
