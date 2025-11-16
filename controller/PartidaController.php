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

        foreach ($categorias as $i => &$cat) {
            $cat['last'] = ($i === count($categorias) - 1);
        }

        $this->renderer->render("partidaIniciada", [
            "categorias" => $categorias
        ]);
    }

    public function jugarPartida()
    {
        if (!isset($_GET["categoria"])) {
            header("Location: /partida/iniciarPartida");
            exit;
        }

        $categoria = $_GET["categoria"];
        $pregunta = $this->model->getPreguntaPorCategoria($categoria);

        if (!$pregunta) {
            die("No hay preguntas cargadas para la categoría: " . $categoria);
        }

        $this->renderer->render("pregunta", [
            "categoria" => $categoria,
            "pregunta" => $pregunta
        ]);
    }

    public function validarRespuesta()
    {
        // DEVOLVER JSON, NO RENDERIZAR
        header("Content-Type: application/json");

        $id = $_POST["id_pregunta"];
        $respuesta = $_POST["respuesta"];

        $correcta = $this->model->getRespuestaCorrecta($id);

        $esCorrecta = ($respuesta === $correcta["correcta"]);

        echo json_encode([
            "correcta" => $esCorrecta
        ]);
    }
}
