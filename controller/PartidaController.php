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

        // 1. Obtener el usuario logueado
        $usuarioId = $_SESSION["usuario_id"] ?? null;

        if (!$usuarioId) {
            header("Location: /login/login");
            exit;
        }

        // 2. Crear la partida
        $partidaId = $this->model->crearPartida($usuarioId);

        // 3. Guardar el ID en la sesión
        $_SESSION["partida_id"] = $partidaId;

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

//    public function validarRespuesta()
//    {
//        // DEVOLVER JSON, NO RENDERIZAR
//        header("Content-Type: application/json");
//
//        $id = $_POST["id_pregunta"];
//        $respuesta = $_POST["respuesta"];
//
//        $correcta = $this->model->getRespuestaCorrecta($id);
//
//        $esCorrecta = ($respuesta === $correcta["correcta"]);
//
//        echo json_encode([
//            "correcta" => $esCorrecta
//        ]);
//    }

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
