<?php

class PartidaController
{

    //private $model;
    private $renderer;

    public function __construct($renderer)
    {

        //$this->model = $model;
        $this->renderer = $renderer;
    }

    public function iniciarPartida()
    {
        echo $this->renderer->render("partidaIniciada");
    }
}
?>