<?php

class RankingController
{

    private $model;
    private $renderer;

    public function __construct($model, $renderer){
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function mostrarRanking(){

        $usuarios = $this->model->obtenerUsuariosOrdenadosPorRatio();

        // Agregar el número de puesto (1, 2, 3...)
        foreach ($usuarios as $i => &$usuario) {
            $usuario['puesto'] = $i + 1;
        }

        $data = ['usuarios' => $usuarios];
        $this->renderer->render('ranking', $data);
    }

}