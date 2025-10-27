<?php
class HomeController{
    private $renderer;

    public function __construct($renderer){
        $this->renderer = $renderer;
    }

    public function mostrarHome(){
        echo $this->renderer->render("vista/homeVista.mustache");
    }
}
?>
