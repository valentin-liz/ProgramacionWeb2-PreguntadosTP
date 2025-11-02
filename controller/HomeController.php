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

    public function obtenerUsuarioLogueado(){
        return $_SESSION['usuario'];
    }

    public function mostrarHome(){
        $this->autenticarUsuarioLogueado();
        $data = [
            "usuario" => $_SESSION["usuario"],
            "logueado" => true,
            "puntos" => $this->model->getPuntosUsuario($_SESSION["usuario"]),
        ];
        $this->renderer->render("home", $data);
    }

}
?>
