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

        if (isset($_SESSION["partida_id"])) {

            $partidaId = $_SESSION["partida_id"];

            // Finalizar la partida anterior
            $this->model->finalizarPartida($partidaId);

            // Limpiar la sesión para permitir nueva partida
            unset($_SESSION["partida_id"]);

            // Redirigir al resumen
            header("Location: /partida/resumen?partidaId=" . $partidaId);

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

        $_SESSION['pregunta_respondida'] = false;

        $this->renderer->render("partidaIniciada", [
            "categorias" => $categorias
        ]);
    }

    public function entregarPregunta()
    {

        if (!isset($_SESSION["partida_id"])) {
            header("Location: /home/mostrarHome");
            exit;
        }

        if (!isset($_GET["categoria"])) {
            header("Location: /partida/iniciarPartida");
            exit;
        }

        $partidaId = $_SESSION["partida_id"];
        $usuarioId = $_SESSION["usuario_id"];
        $categoria = $_GET["categoria"];

        $estado = $this->model->getEstadoPartida($partidaId);

        if ($estado !== 'jugando') {
            header("Location: /partida/resumen?partidaId=$partidaId");
            exit;
        }

        if ($_SESSION['pregunta_respondida'] === true) {

            $partidaId = $_SESSION["partida_id"];
            $this->model->clearPreguntaActual($partidaId);
            header("Location: /partida/ruleta");
            exit;
        }

        $_SESSION['pregunta_respondida'] = false;

        $pregunta = $this->model->getPreguntaActual($partidaId);


        if ($pregunta === null ) {

            $pregunta = $this->model->getPreguntaPorCategoria($categoria, $usuarioId);

            if (!$pregunta) {

                $this->model->reiniciarPreguntasPartida($usuarioId);

                $pregunta = $this->model->getPreguntaPorCategoria($categoria, $usuarioId);

                $this->model->setPreguntaActual($partidaId, $pregunta["id"]);

            }

            $this->model->setPreguntaActual($partidaId, $pregunta["id"]);
        }

        $partida = $this->model->getPartida($partidaId);

        $inicio = strtotime($partida["inicio_pregunta"]);
        $limite = (int)$partida["tiempo_limite_seg"];

        if (!$inicio || !$limite) {
            // por seguridad, reiniciar a 35s
            $inicio = time();
            $limite = 35;
        }

        $restante = $limite - (time() - $inicio);

        $restante = (int) max(0, $restante);

        if ($restante < 0) $restante = 0;

        $this->renderer->render("pregunta", [
            "categoria" => $categoria,
            "pregunta" => $pregunta,
            "tiempo_restante" => $restante,
            "partida_id" => $partidaId
        ]);
    }

    public function responder()
    {

        if (!isset($_POST["id_pregunta"]) || !isset($_POST["respuesta"])) {
            echo json_encode(["error" => "Datos incompletos"]);
            return;
        }

        $preguntaId = $_POST["id_pregunta"];
        $respuesta = $_POST["respuesta"];
        $usuarioId = $_SESSION["usuario_id"];
        $partidaId = $_SESSION["partida_id"];

        $estado = $this->model->getEstadoPartida($partidaId);

        if ($estado !== 'jugando') {
            echo json_encode([
                "error" => "Partida finalizada",
                "partidaFinalizada" => true,
                "redirect" => "/partida/resumen?partida=$partidaId"
            ]);
            return;
        }

        if ($respuesta === "timeout") {

            $this->model->finalizarPartida($partidaId);
            $this->model->clearPreguntaActual($partidaId);
            $this->model->clearRuletaMostrada($partidaId);

            echo json_encode([
                "correcta" => false,
                "partidaFinalizada" => true,
                "partidaId" => $partidaId
            ]);
            return;
        }

        $_SESSION['pregunta_respondida'] = true;

        // Antes de procesar la respuesta
        $preguntaActual = $this->model->getPreguntaActual($partidaId);

        if (!$preguntaActual || $preguntaId != $preguntaActual['id']) {
            echo json_encode(["error" => "Pregunta ya respondida"]);
            return;
        }


        // Delegar validación al modelo
        $resultado = $this->model->verificarRespuesta($preguntaId, $respuesta);

        // Guardar que el usuario ya vio esta pregunta
        $this->model->marcarPreguntaRespondida($usuarioId, $preguntaId);

        // Actualizar puntos si es correcta
        if ($resultado["correcta"]) {

            $this->model->sumarPunto($partidaId, $usuarioId);
            $this->model->clearPreguntaActual($partidaId);
            $this->model->clearRuletaMostrada($partidaId);

            echo json_encode([
                "correcta" => true,
                "partidaFinalizada" => false
            ]);
            return;
        }

        $this->model->finalizarPartida($partidaId);
        $this->model->clearPreguntaActual($partidaId);
        $this->model->clearRuletaMostrada($partidaId);

        echo json_encode([
            "correcta" => false,
            "partidaFinalizada" => true,
            "partidaId" => $partidaId
        ]);


    }


    public function ruleta()
    {

        $partidaId = $_SESSION["partida_id"];

        $ruletaMostrada = $this->model->getRuletaMostrada($partidaId);


        if ($ruletaMostrada === 1) {

            // Finalizar la partida anterior
            $this->model->finalizarPartida($partidaId);

            // Limpiar la sesión para permitir nueva partida
            unset($_SESSION["partida_id"]);

            // Redirigir al resumen
            header("Location: /partida/resumen?partidaId=" . $partidaId);

            exit;
        }

        if (!isset($_SESSION["partida_id"])) {
            header("Location: /login/login");
            exit;
        }

        $categorias = $this->model->getCategorias();

        foreach ($categorias as $i => &$cat) {
            $cat['last'] = ($i === count($categorias) - 1);
        }

        $this->model->setRuletaMostrada($partidaId);

        $_SESSION['pregunta_respondida'] = false;

        $this->renderer->render("partidaIniciada", [
            "categorias" => $categorias
        ]);

    }

    public function salir()
    {
        if (!isset($_SESSION["partida_id"])) {
            header("Location: /home/mostrarHome");
            exit;
        }

        $partidaId = $_SESSION["partida_id"];
        $this->model->finalizarPartida($partidaId);

        unset($_SESSION["partida_id"]);

        header("Location: /partida/resumen?partidaId=".$partidaId);
    }


    public function resumen()
    {
        $partidaId = $_GET["partidaId"] ?? null;

        if (!$partidaId) {
            die("Partida no encontrada");
        }

        // limpiar la partida de la sesión
        unset($_SESSION["partida_id"]);

        $data = $this->model->obtenerResumenPartida($partidaId);

        $this->renderer->render("resumenPartida", $data);
    }

    public function marcarAbandono()
    {
        if (isset($_SESSION["partida_id"])) {

            $this->model->finalizarPartida($_SESSION["partida_id"]);
            unset($_SESSION["partida_id"]);
        }
    }



}
