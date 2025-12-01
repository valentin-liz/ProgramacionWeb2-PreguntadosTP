<?php

class AgregarCategoriaController
{

    private $model;
    private $renderer;

    public function __construct($model, $renderer){
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function mostrarAgregarCategoria() {

        $data = [
            "logueado" => true,

        ];

        $this->renderer->render("agregarCategoria", $data);

    }

    public function guardarCategoria() {

        $camposObligatorios = ["nombre", "color"];

        foreach ($camposObligatorios as $campo) {
            if (!isset($_POST[$campo]) || trim($_POST[$campo]) === "") {

                $data = [
                    "logueado" => true,
                    "error" => "No se pudo agregar la categoria, hay campos vacios",
                ];

                $this->renderer->render("agregarCategoria", $data);
                exit();
            }
        }

        $nombre  = $_POST["nombre"];
        $color = $_POST["color"];

        $ok = $this->model->agregarCategoria($nombre, $color);

        if ($ok) {

            $data = [
                "logueado" => true,
                "exito" => "La categoria se agrego correctamente",
            ];

            $this->renderer->render("agregarCategoria", $data);

        } else {

            $data = [
                "logueado" => true,
                "error" => "No se pudo agregar la categoria, intentalo de nuevamente",
            ];

            $this->renderer->render("agregarCategoria", $data);
        }
    }

}