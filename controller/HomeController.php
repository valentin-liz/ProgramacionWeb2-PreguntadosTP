<?php

require_once 'helper/AuthHelper.php';

class HomeController{

    private $model;
    private $renderer;

    public function __construct($model, $renderer){
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function autenticarUsuarioLogueado(){
        AuthHelper::checkLogin();
    }

    public function autenticarQueUsuarioSeaJugador(){
        AuthHelper::verificarJugador();
    }

    public function autenticarQueUsuarioSeaEditor(){
        AuthHelper::verificarEditor();
    }

    public function obtenerRolUsuarioLogueado(){
        return $_SESSION['rol'];
    }

    public function mostrarHome(){

        $this->autenticarUsuarioLogueado();

        switch ($this->obtenerRolUsuarioLogueado()) {

            case 'jugador':

                $this->autenticarQueUsuarioSeaJugador();

                $data = [
                    "usuario" => $_SESSION["usuario"],
                    "logueado" => true,
                    "puntos" => $this->model->getPuntosUsuario($_SESSION["usuario"]),
                ];

                $this->renderer->render("home", $data);
                break;

            case 'editor':

                $this->autenticarQueUsuarioSeaEditor();

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
