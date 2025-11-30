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
//        $sql = "
//        SELECT p.*
//        FROM preguntas p
//        JOIN categoria c ON p.categoria_id = c.id
//        LEFT JOIN preguntas_respondidas pr
//            ON pr.pregunta_id = p.id
//            AND pr.usuario_id = ?
//        WHERE c.nombre = ?
//        AND pr.pregunta_id IS NULL
//        ORDER BY RAND()
//        LIMIT 1
//    ";
//
//        $stmt = $this->conexion->prepare($sql);
//        $stmt->bind_param("is", $usuarioId, $categoria);
//        $stmt->execute();
//
//        return $stmt->get_result()->fetch_assoc();

        // 1. Traer el nivel del usuario
        $sql = "SELECT nivel FROM usuarios WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $nivelUsuario = $stmt->get_result()->fetch_assoc()["nivel"];

        // 2. Determinar niveles permitidos según el nivel del usuario
        $nivelesPermitidos = $this->nivelesPermitidosSegunUsuario($nivelUsuario);

        // 3. Preparar placeholders para el IN()
        $nivelesStr = "'" . implode("','", $nivelesPermitidos) . "'";

        // 4. Preparar query adaptada
        $sql = "
        SELECT p.*
        FROM preguntas p
        JOIN categoria c ON p.categoria_id = c.id
        LEFT JOIN preguntas_respondidas pr 
            ON pr.pregunta_id = p.id 
            AND pr.usuario_id = ?
        WHERE c.nombre = ?
        AND pr.pregunta_id IS NULL
        AND p.nivel IN ($nivelesStr)
        ORDER BY RAND()
        LIMIT 1
    ";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("is", $usuarioId, $categoria);
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
        $sql = "UPDATE partida 
                SET pregunta_actual_id = ?, 
                    last_activity = NOW(),
                    inicio_pregunta = NOW(),
                    tiempo_limite_seg = 35 
                WHERE id = ?";

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

    public function reiniciarPreguntasPartida($usuarioId)
    {
        $sql = "DELETE FROM preguntas_respondidas WHERE usuario_id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
    }

    public function preguntaExpirada($partidaId)
    {
        $sql = "SELECT inicio_pregunta, tiempo_limite_seg 
            FROM partida WHERE id = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $partidaId);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();

        if (!$res) return true;

        $inicio = strtotime($res["inicio_pregunta"]);
        $limite = $res["tiempo_limite_seg"];

        return (time() - $inicio) > $limite;
    }

    public function setPreguntaActualSinReiniciarTiempo($partidaId, $preguntaId)
    {
        $sql = "UPDATE partida 
            SET pregunta_actual_id = ?, 
                last_activity = NOW()
            WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $preguntaId, $partidaId);
        $stmt->execute();
    }

    public function getPreguntaById($id)
    {
        $sql = "SELECT * FROM preguntas WHERE id = ? LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getPreguntaActual($partidaId)
    {
        $sql = "
    SELECT p.*
    FROM partida pa
    JOIN preguntas p ON pa.pregunta_actual_id = p.id
    WHERE pa.id = ?
    LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $partidaId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function clearPreguntaActual($partidaId)
    {
        $sql = "UPDATE partida
            SET pregunta_actual_id = NULL,
                inicio_pregunta = NULL,
                tiempo_limite_seg = NULL,
                last_activity = NOW()
            WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $partidaId);
        $stmt->execute();
    }

    public function setRuletaMostrada($partidaId)
    {
        $query = "UPDATE partida SET ruleta_mostrada = 1 WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $partidaId);
        $stmt->execute();
    }

    public function clearRuletaMostrada($partidaId)
    {
        $query = "UPDATE partida SET ruleta_mostrada = 0 WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $partidaId);
        $stmt->execute();
    }

    public function getRuletaMostrada($partidaId)
    {
        $sql = "SELECT ruleta_mostrada FROM partida WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $partidaId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        return $result ? (int)$result['ruleta_mostrada'] : null;
    }

    public function guardarReporte($preguntaId, $mensaje)
    {
        $sql = "INSERT INTO reportes (pregunta_id, mensaje) 
            VALUES (?, ?)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("is", $preguntaId, $mensaje);
        $stmt->execute();
    }

    public function actualizarStatsDeUsuario($usuarioId, $acerto)
    {
        // 1) sumar vistos
        $sql = "UPDATE usuarios SET vistos = vistos + 1 WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();

        // 2) si acertó, sumar hit
        if ($acerto) {
            $sql = "UPDATE usuarios SET hits = hits + 1 WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("i", $usuarioId);
            $stmt->execute();
        }

        // 3) recalcular ratio
        $sql = "UPDATE usuarios 
            SET ratio = CASE 
                WHEN vistos = 0 THEN 0 
                ELSE hits / vistos 
            END
            WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();

        // 4) actualizar nivel según ratio
        $sql = "UPDATE usuarios
            SET nivel = CASE
                WHEN vistos < 5 THEN 'nuevo'
                WHEN vistos >= 10 AND ratio >= 0.70 THEN 'experto'
                WHEN vistos >= 5 AND ratio >= 0.40 AND ratio < 0.70 THEN 'medio'
                WHEN vistos >= 10 AND ratio < 0.40 THEN 'basico'
                ELSE 'medio'
            END
            WHERE id = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
    }


    public function actualizarStatsDePregunta($preguntaId, $acerto)
    {
        // 1) sumar vista
        $sql = "UPDATE preguntas SET veces_vista = veces_vista + 1 WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $preguntaId);
        $stmt->execute();

        // 2) si acertó sumar acierto
        if ($acerto) {
            $sql = "UPDATE preguntas SET veces_acertada = veces_acertada + 1 WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("i", $preguntaId);
            $stmt->execute();
        }

        // 3) recalcular ratio
        $sql = "UPDATE preguntas 
            SET ratio = CASE 
                WHEN veces_vista = 0 THEN 0
                ELSE veces_acertada / veces_vista
            END
            WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $preguntaId);
        $stmt->execute();

        // 4) actualizar nivel según ratio
        $sql = "UPDATE preguntas
            SET nivel = CASE
                WHEN veces_vista < 5 THEN 'normal'
                WHEN ratio < 0.40 THEN 'dificil'
                WHEN ratio < 0.70 THEN 'normal'
                ELSE 'facil'
            END
            WHERE id = ?";

        $sql = "UPDATE preguntas
            SET nivel = CASE
                WHEN veces_vista < 5 THEN 'normal'
                WHEN veces_vista >= 10 AND ratio >= 0.70 THEN 'facil'
                WHEN veces_vista >= 5 AND ratio >= 0.40 AND ratio < 0.70 THEN 'normal'
                WHEN veces_vista >= 10 AND ratio < 0.40 THEN 'dificil'
                ELSE 'normal'
            END
            WHERE id = ?";


        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $preguntaId);
        $stmt->execute();
    }

    private function nivelesPermitidosSegunUsuario($nivelUsuario)
    {
        switch ($nivelUsuario) {

            case 'nuevo':
                return ['facil', 'normal'];

            case 'basico':
                return ['facil'];

            case 'medio':
                return ['normal'];

            case 'experto':
                return ['normal', 'dificil'];

            default:
                return ['normal']; // fallback
        }
    }


}
