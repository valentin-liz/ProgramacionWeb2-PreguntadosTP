<?php

class ModificarCategoriaController
{

    private $model;
    private $renderer;

    public function __construct($model, $renderer){
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function mostrarModificarCategoria() {

        $id = $_GET["id"];
        $categoria = $this->model->getCategoriaById($id);

        $this->renderer->render("modificarCategoria", [
            "categoria" => $categoria,
            "logueado" => true,
        ]);
    }

    public function guardarModificacion()
    {
        $campos = ["id", "nombre", "color"];

        foreach ($campos as $campo) {
            if (!isset($_POST[$campo]) || trim($_POST[$campo]) === "") {

                $this->renderer->render("modificarCategoria", [
                    "logueado" => true,
                    "error" => "Hay campos vacios, vuelva a intentarlo nuevamente"
                ]);
                exit();
            }
        }

        $id = $_POST["id"];
        $nombre= $_POST["nombre"];
        $color = $_POST["color"];

        $ok = $this->model->modificarCategoria($id, $nombre, $color);

        if ($ok) {

            $this->renderer->render("modificarCategoria", [
                "logueado" => true,
                "exito" => "Modificaciones gurdadas exitosamente"
            ]);
        } else {

            $this->renderer->render("modificarCategoria", [
                "logueado" => true,
                "error" => "No se pudo modificar la categoria, por favor intenta nuevamente"
            ]);
        }

        exit();
    }


}