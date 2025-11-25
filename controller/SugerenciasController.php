<?php

class SugerenciasController
{

    private $model;
    private $renderer;

    public function __construct($model, $renderer){
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function mostrarSugerencias()
    {

        $sugerencias = $this->model->getTodasLasSugerencias();

        $data = [
            "logueado" => true,
            "sugerencias" => $sugerencias
        ];

        $this->renderer->render("sugerencias", $data);

    }

    public function agregarSugerencia()
    {
        $id = $_POST["sugerencia_id"];

        $sugerencia = $this->model->getSugerenciaById($id);

        if (!$sugerencia) {
            header("Location: /sugerencias/mostrarSugerencias");
            exit();
        }

        $ok = $this->model->insertarPreguntaDesdeSugerencia($sugerencia);

        if ($ok) {

            $this->model->borrarSugerencia($id);
        }

        header("Location: /sugerencias/mostrarSugerencias");
        exit();
    }




}