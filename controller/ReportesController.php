<?php

class ReportesController
{

    private $model;
    private $renderer;

    public function __construct($model, $renderer){
        $this->model = $model;
        $this->renderer = $renderer;
    }

    function mostrarReportes()
    {

        $reportes = $this->model->getTodosLosReportes();

        $data = [
            "logueado" => true,
            "reportes" => $reportes
        ];

        $this->renderer->render("reportes", $data);

    }

}