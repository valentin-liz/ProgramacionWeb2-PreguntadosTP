<?php

class PartidaController
{

    private $model;
    private $renderer;

    public function __construct($model, $renderer)
    {

        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function iniciarPartida()
    {
        $categorias = $this->model->getCategorias();

        // Agregar campo 'last' para el JS
        foreach ($categorias as $i => &$cat) {
            $cat['last'] = ($i === count($categorias) - 1);
        }

        $data = [
            'categorias' => $categorias
        ];

        $this->renderer->render("partidaIniciada", $data);
    }
}
?>