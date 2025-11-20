<?php

class AgregarPreguntaController
{

    private $model;
    private $renderer;

    public function __construct($model, $renderer){
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function agregarPregunta() {

        $data = [
            "logueado" => true,
        ];

        $this->renderer->render("agregarPregunta", $data);

    }

}