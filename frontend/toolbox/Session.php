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

    public function unsetSession()
    {
        $this->setSession('role','');
        $this->setSession('connected', false);
    }
}