<?php

require_once 'helper/AuthHelper.php';

class HomeController{
    private $renderer;

    public function __construct($renderer){
        $this->renderer = $renderer;
    }

    public function autenticarUsuarioLogueado(){
        AuthHelper::checkLogin();
    }

    public function mostrarHome(){
        $this->autenticarUsuarioLogueado();
        $data = [
            "usuario" => $_SESSION["usuario"],
            "logueado" => true
        ];
        $this->renderer->render("home", $data);
    }

}
?>
