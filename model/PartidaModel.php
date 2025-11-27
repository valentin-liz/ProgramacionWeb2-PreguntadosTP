<?php

class PartidaModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getCategorias()
    {
        $query = "SELECT nombre, color FROM categoria";
        $resultado = $this->conexion->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function getPreguntaPorCategoria($categoria, $usuarioId)
    {
        $sql = "SELECT p.*
            FROM preguntas p
            JOIN categoria c ON p.categoria_id = c.id
            WHERE c.nombre = ?
            ORDER BY RAND()
            LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $categoria);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function getConexion()
    {
        return $this->conexion;
    }

    public function crearPartida($usuarioId)
    {
        $sql = "INSERT INTO partida (usuario_id) VALUES (?)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();

        // devolver el ID de la partida recién creada
        return $this->conexion->insert_id;
    }

    public function setPreguntaActual($partidaId, $preguntaId)
    {
        $sql = "UPDATE partida SET pregunta_actual_id = ?, last_activity = NOW() WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $preguntaId, $partidaId);
        $stmt->execute();
    }

    public function verificarRespuesta($preguntaId, $respuesta)
    {
        $sql = "SELECT correcta FROM preguntas WHERE id = ? LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $preguntaId);
        $stmt->execute();
        $result = $stmt->get_result();
        $pregunta = $result->fetch_assoc();

        if (!$pregunta) {
            return ["error" => "Pregunta no encontrada"];
        }

        $respuestaCorrecta = $pregunta["correcta"]; // 'A', 'B', 'C' o 'D'

        return [
            "correcta" => ($respuestaCorrecta === strtoupper($respuesta)),
            "respuesta_correcta" => $respuestaCorrecta
        ];
    }


    public function sumarPunto($partidaId, $usuarioId)
    {
        $sql = "UPDATE partida 
            SET puntos = puntos + 1 
            WHERE id = ? AND usuario_id = ?
            AND estado = 'jugando'";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $partidaId, $usuarioId);
        $stmt->execute();
    }


    public function marcarPreguntaRespondida($usuarioId, $preguntaId)
    {
        $sql = "INSERT IGNORE INTO preguntas_respondidas (usuario_id, pregunta_id)
            VALUES (?, ?)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $usuarioId, $preguntaId);
        $stmt->execute();
    }

    public function finalizarPartida($partidaId)
    {
        $sql = "UPDATE partida 
            SET estado = 'finalizada', fin = NOW(),
                tiempo_total_seg = TIMESTAMPDIFF(SECOND, inicio, NOW())
            WHERE id = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $partidaId);
        $stmt->execute();
    }

    public function getPartida($id)
    {
        $sql = "SELECT * FROM partida WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function obtenerResumenPartida($partidaId)
    {

        $sql = "
        SELECT p.id, p.usuario_id, p.puntos, p.estado, 
               p.inicio, p.fin, p.tiempo_total_seg, u.usuario AS usuario
        FROM partida p
        JOIN usuarios u ON u.id = p.usuario_id
        WHERE p.id = ?
    ";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $partidaId);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 0) {
            return null;
        }

        return $res->fetch_assoc();
    }

    public function getEstadoPartida($partidaId)
    {
        $sql = "SELECT estado FROM partida WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $partidaId);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ? $res["estado"] : null;
    }



}
