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

        if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "administrador") {
            die("403 - No tenés permiso para ver este reporte");
        }

        $tipo = $_GET["periodo"] ?? "mes";
        $fechaBase = $_GET["fecha"] ?? date("Y-m-d");
        if (!$fechaBase) $fechaBase = date("Y-m-d");

        [$desde, $hasta] = $this->model->calcularRangoFechas($tipo, $fechaBase);

        $data = [
            "tipoFiltro" => $tipo,
            "fechaBase" => $fechaBase,
            "desde" => $desde,
            "hasta" => $hasta,

            "cantidadUsuarios" => $this->model->getCantidadUsuarios(),
            "cantidadPartidas" => $this->model->getCantidadPartidas(),
            "cantidadPreguntasJuego" => $this->model->getCantidadPreguntasJuego(),
            "cantidadPreguntasCreadas" => $this->model->getCantidadPreguntasCreadas(),
            "cantidadUsuariosNuevos" => $this->model->getCantidadUsuariosNuevos($desde, $hasta),

            "porcentajeAciertos" => $this->model->getPorcentajeAciertosPorUsuario(),
            "usuariosPorPais" => $this->model->getUsuariosPorPais(),
            "usuariosPorSexo" => $this->model->getUsuariosPorSexo(),
            "usuariosPorGrupoEdad" => $this->model->getUsuariosPorGrupoEdad(),
        ];

        $data["porcentajeAciertos_JSON"] = json_encode($data["porcentajeAciertos"]);
        $data["usuariosPorPais_JSON"] = json_encode($data["usuariosPorPais"]);
        $data["usuariosPorSexo_JSON"] = json_encode($data["usuariosPorSexo"]);
        $data["usuariosPorGrupoEdad_JSON"] = json_encode($data["usuariosPorGrupoEdad"]);

        $this->renderer->render("adminDashboard", $data);
    }
}
