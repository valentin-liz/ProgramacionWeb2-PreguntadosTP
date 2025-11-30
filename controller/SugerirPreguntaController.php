<?php

class SugerirPreguntaController
{
    private $model;
    private $renderer;

    public function __construct($model, $renderer){
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function mostrarSugerirPregunta() {

        $categorias = $this->model->getCategorias();

        $data = [
            "logueado" => true,
            'categorias' => $categorias,
        ];

        $this->renderer->render("sugerirPregunta", $data);

    }

    public function guardarSugerenciaDePregunta()
    {

        $camposObligatorios = ["pregunta", "categoria_id", "opcion_a", "opcion_b", "opcion_c", "opcion_d", "opcionCorrecta"];

        foreach ($camposObligatorios as $campo) {
            if (!isset($_POST[$campo]) || trim($_POST[$campo]) === "") {

                $categorias = $this->model->getCategorias();

                $data = [
                    "logueado" => true,
                    'categorias' => $categorias,
                    "error" => "No se pudo agregar la pregunta, hay campos vacios",
                ];

                $this->renderer->render("sugerirPregunta", $data);
                exit();
            }
        }

        $pregunta  = $_POST["pregunta"];
        $categoria = $_POST["categoria_id"];
        $a = $_POST["opcion_a"];
        $b = $_POST["opcion_b"];
        $c = $_POST["opcion_c"];
        $d = $_POST["opcion_d"];
        $correcta = $_POST["opcionCorrecta"];


        $ok = $this->model->agregarSugerencia($pregunta, $categoria, $a, $b, $c, $d, $correcta);

        if ($ok) {

            $categorias = $this->model->getCategorias();

            $data = [
                "logueado" => true,
                'categorias' => $categorias,
                "exito" => "La sugerencia se agrego correctamente",
            ];

            $this->renderer->render("sugerirPregunta", $data);

        } else {

            $categorias = $this->model->getCategorias();

            $data = [
                "logueado" => true,
                'categorias' => $categorias,
                "error" => "No se pudo agregar la sugerencia, intentalo de nuevamente",
            ];

            $this->renderer->render("sugerirPregunta", $data);
        }
    }

}