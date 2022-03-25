<?php

namespace Toolbox;

class Session
{

    public function setSession($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function getSession($key)
    {
        if($_SESSION[$key]){
            return $_SESSION[$key];
        }

        return false;
    }
}