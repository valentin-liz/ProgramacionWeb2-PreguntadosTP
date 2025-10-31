<?php

class AuthHelper
{

    public static function checkLogin() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario'])) {
            header("Location: /login/login");
            exit;
        }
    }

}