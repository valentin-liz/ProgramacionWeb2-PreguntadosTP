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

    public function login()

    {

        if (isset($_POST["usuario"]) && isset($_POST["password"])) {
            $resultado = $this->model->getUserWith($_POST["usuario"], $_POST["password"]);

            if ($resultado) {
                $_SESSION["usuario"] = $resultado["usuario"];
                $_SESSION["rol"] = $resultado["rol"];
                $_SESSION["usuario_id"] = $resultado["id"];

                header("Location: /home/mostrarHome");

            } else {

                $this->renderer->render("login", ["error" => "Usuario o clave incorrecta"]);
            }
        } else {
            // Primera vez que se entra al login, sin enviar form
            $data = [
                "logueado" => false
            ];
            $this->renderer->render("login", $data);
        }

    }


}


