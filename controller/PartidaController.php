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

        $data = [
            "categoria" => $categoria,
            "pregunta" => $pregunta
        ];

        $this->renderer->render("pregunta", $data);
    }

     public function validarRespuesta()
    {
        $id = $_POST["id_pregunta"];
        $respuesta = $_POST["respuesta"];
        $categoria = $_POST["categoria"];

        // PEDIR LA RESPUESTA CORRECTA
        $sql = "SELECT correcta FROM preguntas WHERE id = ?";
        $stmt = $this->model->getConexion()->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        $esCorrecta = ($respuesta === $resultado["correcta"]);

        $data = [
            "esCorrecta" => $esCorrecta,
            "categoria" => $categoria
        ];

        $this->renderer->render("pregunta", $data);
    }
}
