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

    public static function verificarJugador() {

        if ($_SESSION['rol'] !== 'jugador') {
            header("Location: /login/login");
            exit;
        }
    }

    public static function verificarEditor() {

        if ($_SESSION['rol'] !== "editor") {
            header("Location: /login/login");
            exit;
        }
    }

}