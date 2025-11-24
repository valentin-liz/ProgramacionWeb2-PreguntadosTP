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

    public function validarRespuesta()
{
    session_start();
    header("Content-Type: application/json");

    // Datos recibidos del AJAX
    $preguntaId = $_POST["id_pregunta"];
    $respuesta = $_POST["respuesta"];

    // Usuario actual
    $usuario = $_SESSION["usuario"];

    // Obtener la respuesta correcta
    $correcta = $this->model->getRespuestaCorrecta($preguntaId);
    $esCorrecta = ($respuesta === $correcta["correcta"]) ? 1 : 0;

    // ================================================
    // ✔ GUARDAR RESPUESTA EN BD
    // ================================================
    $conexion = $this->model->getConexion();

    $sql = "INSERT INTO partidas_usuario (usuario, pregunta_id, respondida_correcta)
            VALUES (?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sii", $usuario, $preguntaId, $esCorrecta);
    $stmt->execute();

    // ================================================
    // ✔ SUMAR PUNTOS (opcional)
    // ================================================
    if ($esCorrecta) {
        $sql2 = "UPDATE usuarios SET puntos = puntos + 10 WHERE usuario = ?";
        $stmt2 = $conexion->prepare($sql2);
        $stmt2->bind_param("s", $usuario);
        $stmt2->execute();
    }

    // ================================================
    // ✔ RESPUESTA AL FRONT
    // ================================================
    echo json_encode([
        "correcta" => $esCorrecta
    ]);
}

}
?>