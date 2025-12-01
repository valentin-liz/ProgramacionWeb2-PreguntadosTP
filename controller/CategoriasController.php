<?php

class CategoriasController
{

    private $model;
    private $renderer;

    public function __construct($model, $renderer){
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function mostrarCategorias(){
        $this->renderer->render("categorias", [
            "categorias" => $this->model->getCategorias(),
            "logueado" => true,
        ]);
    }

    public function borrarCategoria()
    {
        $id = $_POST["id"];

        $ok = $this->model->borrarCategoria($id);

        if ($ok) {
            header("Location: /categorias/mostrarCategorias");
        } else {
            header("Location: /categorias/mostrarCategorias");
        }

        exit();
    }

}