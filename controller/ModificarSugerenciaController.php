<?php

class ModificarSugerenciaController
{

    private $model;
    private $renderer;

    public function __construct($model, $renderer){
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function mostrarModificarSugerencia(){

        $id = $_GET['id'];
        $sugerencia = $this->model->getSugerenciaById($id);
        $categorias = $this->model->getCategorias();

        foreach ($categorias as &$categoria) {
            $categoria["selected"] = ($categoria["id"] == $sugerencia["categoria_id"]);
        }
        unset($categoria);

        $sugerencia["correctaA"] = ($sugerencia["correcta"] === "A");
        $sugerencia["correctaB"] = ($sugerencia["correcta"] === "B");
        $sugerencia["correctaC"] = ($sugerencia["correcta"] === "C");
        $sugerencia["correctaD"] = ($sugerencia["correcta"] === "D");

        $data = [
            "logueado" => true,
            "sugerencia" => $sugerencia,
            "categorias" => $categorias
        ];

        $this->renderer->render("modificarSugerencia", $data);

    }


    public function guardarModificacion()
    {
        $campos = ["id", "pregunta", "categoria_id", "opcion_a", "opcion_b", "opcion_c", "opcion_d", "opcionCorrecta"];

        foreach ($campos as $campo) {
            if (!isset($_POST[$campo]) || trim($_POST[$campo]) === "") {

                $this->renderer->render("modificarPregunta", [
                    "error" => "Hay campos vacios, vuelva a intentarlo nuevamente"
                ]);
                exit();
            }
        }

        $id = $_POST["id"];
        $pregunta  = $_POST["pregunta"];
        $categoria = $_POST["categoria_id"];
        $a = $_POST["opcion_a"];
        $b = $_POST["opcion_b"];
        $c = $_POST["opcion_c"];
        $d = $_POST["opcion_d"];
        $correcta = $_POST["opcionCorrecta"];

        $ok = $this->model->modificarSugerencia($id, $pregunta, $categoria, $a, $b, $c, $d, $correcta);

        if ($ok) {

            $this->renderer->render("modificarSugerencia", [
                "exito" => "Modificaciones gurdadas exitosamente"
            ]);
        } else {

            $this->renderer->render("modificarSugerencia", [
                "error" => "No se pudo modificar la pregunta, por favor intenta nuevamente"
            ]);
        }

        exit();
    }

}