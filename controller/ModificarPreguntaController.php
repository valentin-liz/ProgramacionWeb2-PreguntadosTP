<?php

class ModificarPreguntaController
{

    private $model;
    private $renderer;

    public function __construct($model, $renderer){
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function mostrarModificarPregunta()
    {

        $id = $_GET["id"];
        $pregunta = $this->model->getPreguntaById($id);
        $categorias = $this->model->getCategorias();

        foreach ($categorias as &$categoria) {
            $categoria["selected"] = ($categoria["id"] == $pregunta["categoria_id"]);
        }
        unset($categoria);

        // Generar flags para Mustache
        $pregunta["correctaA"] = ($pregunta["correcta"] === "A");
        $pregunta["correctaB"] = ($pregunta["correcta"] === "B");
        $pregunta["correctaC"] = ($pregunta["correcta"] === "C");
        $pregunta["correctaD"] = ($pregunta["correcta"] === "D");

        $this->renderer->render("modificarPregunta", [
            "pregunta" => $pregunta,
            "categorias" => $categorias
        ]);
    }

    public function guardarModificacion()
    {
        $campos = ["id", "categoria_id", "pregunta", "opcion_a", "opcion_b", "opcion_c", "opcion_d", "opcionCorrecta"];

        foreach ($campos as $campo) {
            if (!isset($_POST[$campo]) || trim($_POST[$campo]) === "") {

                $this->renderer->render("modificarPregunta", [
                    "error" => "Hay campos vacios, vuelva a intentarlo nuevamente"
                ]);
                exit();
            }
        }

        $id = $_POST["id"];
        $categoria = $_POST["categoria_id"];
        $pregunta  = $_POST["pregunta"];
        $a = $_POST["opcion_a"];
        $b = $_POST["opcion_b"];
        $c = $_POST["opcion_c"];
        $d = $_POST["opcion_d"];
        $correcta = $_POST["opcionCorrecta"];

        $ok = $this->model->modificarPregunta($id, $categoria, $pregunta, $a, $b, $c, $d, $correcta);

        if ($ok) {

            $this->renderer->render("modificarPregunta", [
                "exito" => "Modificaciones gurdadas exitosamente"
            ]);
        } else {

            $this->renderer->render("modificarPregunta", [
                "error" => "No se pudo modificar la pregunta, por favor intenta nuevamente"
            ]);
        }

        exit();
    }





}