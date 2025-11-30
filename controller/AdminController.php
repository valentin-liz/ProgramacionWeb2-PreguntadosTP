<?php

class AdminController
{
    private $model;
    private $renderer;

    public function __construct($model, $renderer)
    {
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function dashboard()
    {
        session_start();

        if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "administrador") {
            die("403 - No tenés permiso para ver este reporte");
        }

        $tipo = $_GET["periodo"] ?? "mes";
        $fechaBase = $_GET["fecha"] ?? date("Y-m-d");

        [$desde, $hasta] = $this->model->calcularRangoFechas($tipo, $fechaBase);

        $data = [
            "tipoFiltro" => $tipo,
            "fechaBase" => $fechaBase,
            "desde" => $desde,
            "hasta" => $hasta,

            "cantidadUsuarios" => $this->model->getCantidadUsuarios($desde,$hasta),
            "cantidadPartidas" => $this->model->getCantidadPartidas($desde,$hasta),
            "cantidadPreguntasJuego" => $this->model->getCantidadPreguntasJuego($desde,$hasta),
            "cantidadPreguntasCreadas" => $this->model->getCantidadPreguntasCreadas($desde,$hasta),
            "cantidadUsuariosNuevos" => $this->model->getCantidadUsuariosNuevos($desde,$hasta),

            "porcentajeAciertos" => $this->model->getPorcentajeAciertosPorUsuario($desde,$hasta),
            "usuariosPorPais" => $this->model->getUsuariosPorPais($desde,$hasta),
            "usuariosPorSexo" => $this->model->getUsuariosPorSexo($desde,$hasta),
            "usuariosPorGrupoEdad" => $this->model->getUsuariosPorGrupoEdad($desde,$hasta),
        ];

        // JSON de aciertos
        $data["porcentajeAciertos_JSON"] = json_encode($data["porcentajeAciertos"]);

        $this->renderer->render("adminDashboard", $data);
    }
}