<?php

class SugerirPreguntaModel
{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getCategorias()
    {
        $sql = "SELECT id, nombre FROM categoria ORDER BY nombre ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function agregarSugerencia($pregunta, $categoria_id, $opcion_a, $opcion_b, $opcion_c, $opcion_d, $correcta)
    {
        $sql = "INSERT INTO sugerencias 
            (pregunta, categoria_id, opcion_a, opcion_b, opcion_c, opcion_d, correcta) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
        }

        $stmt->bind_param("sisssss",
            $pregunta,
            $categoria_id,
            $opcion_a,
            $opcion_b,
            $opcion_c,
            $opcion_d,
            $correcta
        );

        return $stmt->execute();
    }

}