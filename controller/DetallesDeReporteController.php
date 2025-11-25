<?php

class DetallesDeReporteController
{

    private $model;
    private $renderer;

    public function __construct($model, $renderer)
    {
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function mostrarDetallesDeReporte() {

        $id = $_GET["id"];
        $reporte = $this->model->getReporteById($id);

        $data = [
            "logueado" => true,
            "reporte" => $reporte
        ];

        $this->renderer->render("detallesDeReporte", $data);
    }

    public function finalizarReporte()
    {
        $id = $_POST["reporte_id"];

        $ok = $this->model->borrarReporte($id);

        if ($ok) {
            header("Location: /reportes/mostrarReportes");
        } else {
            header("Location: /reportes/mostrarReportes");
        }

        exit();
    }

}