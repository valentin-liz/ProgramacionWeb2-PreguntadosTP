<?php
class HomeController{
    private $renderer;

    public function __construct($renderer){
        $this->renderer = $renderer;
    }

    public function mostrarHome(){
        $this->renderer->render("home");
    }
}
?>
