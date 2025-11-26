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

    public function getPreguntaPorCategoria($categoria)
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

    public function getRespuestaCorrecta($idPregunta)
    {
        $sql = "SELECT correcta FROM preguntas WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idPregunta);
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


}
