<?php

class AgregarPreguntaController
{

    private $model;
    private $renderer;

    public function __construct($model, $renderer){
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function mostrarAgregarPregunta() {

        $categorias = $this->model->getCategorias();

        $data = [
            "logueado" => true,
            'categorias' => $categorias,
        ];

        $this->renderer->render("agregarPregunta", $data);

    }

    public function guardarPregunta()
    {

        $camposObligatorios = ["categoria_id", "pregunta", "opcion_a", "opcion_b", "opcion_c", "opcion_d", "opcionCorrecta"];

        foreach ($camposObligatorios as $campo) {
            if (!isset($_POST[$campo]) || trim($_POST[$campo]) === "") {

                $categorias = $this->model->getCategorias();

                $data = [
                    "logueado" => true,
                    'categorias' => $categorias,
                    "error" => "No se pudo agregar la pregunta, hay campos vacios",
                ];

                $this->renderer->render("agregarPregunta", $data);
                exit();
            }
        }

        $categoria = $_POST["categoria_id"];
        $pregunta  = $_POST["pregunta"];
        $a = $_POST["opcion_a"];
        $b = $_POST["opcion_b"];
        $c = $_POST["opcion_c"];
        $d = $_POST["opcion_d"];
        $correcta = $_POST["opcionCorrecta"];


        $ok = $this->model->agregarPregunta($categoria, $pregunta, $a, $b, $c, $d, $correcta);

        if ($ok) {

            $categorias = $this->model->getCategorias();

            $data = [
                "logueado" => true,
                'categorias' => $categorias,
                "exito" => "La pregunta se agrego correctamente",
            ];

            $this->renderer->render("agregarPregunta", $data);

        } else {

            $categorias = $this->model->getCategorias();

            $data = [
                "logueado" => true,
                'categorias' => $categorias,
                "error" => "No se pudo agregar la pregunta, intentalo de nuevamente",
            ];

            $this->renderer->render("agregarPregunta", $data);
        }
    }



}