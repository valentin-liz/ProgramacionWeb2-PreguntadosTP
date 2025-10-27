<?php

class LoginController
{
    private $model;
    private $renderer;

    public function __construct($model, $renderer)
    {
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function base()
    {
        $this->login();
    }

    public function loginForm()
    {
        $this->renderer->render("login");
    }

    public function login()
    {

        if (isset($_POST["usuario"]) && isset($_POST["password"])) {
            $resultado = $this->model->getUserWith($_POST["usuario"], $_POST["password"]);

            if ($resultado) {
                $_SESSION["usuario"] = $resultado["usuario"];
                $this->redirectToIndex();
            } else {
                $this->renderer->render("login", ["error" => "Usuario o clave incorrecta"]);
            }
        } else {
            // Primera vez que se entra al login, sin enviar form
            $this->renderer->render("login");
        }

    }

    public function logout()
    {
        session_destroy();
        $this->redirectToIndex();
    }

    public function redirectToIndex()
    {
        header("Location: /");
        exit;
    }

}

