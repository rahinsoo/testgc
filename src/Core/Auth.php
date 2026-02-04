<?php
namespace Core;

class Auth
{
    public static function check()
    {
        session_start();
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }
}
