<?php

require_once 'helper/AuthHelper.php';

class HomeController{

    private $model;
    private $renderer;

    public function __construct($model, $renderer){
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function obtenerRolUsuarioLogueado(){
        return $_SESSION['rol'];
    }

    public function mostrarHome(){

        switch ($this->obtenerRolUsuarioLogueado()) {

            case 'jugador':

                $data = [
                    "usuario" => $_SESSION["usuario"],
                    "logueado" => true,
                    "puntos" => $this->model->getPuntosUsuario($_SESSION["usuario"]),
                ];

                $this->renderer->render("home", $data);
                break;

            case 'editor':

                $preguntas = $this->model->getTodasLasPreguntas();

                $data = [
                    "usuario" => $_SESSION["usuario"],
                    "logueado" => true,
                    "preguntas" => $preguntas
                ];

                $this->renderer->render("homeEditor", $data);
                break;
        }
    }

    public function borrarPregunta()
    {
        $id = $_POST["pregunta_id"];

        $ok = $this->model->borrarPregunta($id);

        if ($ok) {
            header("Location: /home/mostrarHome");
        } else {
            header("Location: /home/mostrarHome");
        }

        exit();
    }


}
?>
