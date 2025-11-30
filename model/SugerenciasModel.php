<?php

class SugerenciasModel
{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getTodasLasSugerencias()
    {
        $sql = "SELECT s.*, c.nombre AS categoria_nombre
            FROM sugerencias s
            JOIN categoria c ON s.categoria_id = c.id
            ORDER BY s.id";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getSugerenciaById($id)
    {
        $sql = "SELECT * FROM sugerencias WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function insertarPreguntaDesdeSugerencia($s)
    {
        $sql = "INSERT INTO preguntas 
            (categoria_id, pregunta, opcion_a, opcion_b, opcion_c, opcion_d, correcta)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param(
            "issssss",
            $s["categoria_id"],
            $s["pregunta"],
            $s["opcion_a"],
            $s["opcion_b"],
            $s["opcion_c"],
            $s["opcion_d"],
            $s["correcta"]
        );

        return $stmt->execute();
    }

    public function borrarSugerencia($id)
    {
        $sql = "DELETE FROM sugerencias WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }





}