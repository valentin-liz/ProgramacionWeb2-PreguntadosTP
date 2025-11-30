<?php

class ModificarPreguntaModel
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

    public function getPreguntaById($id)
    {
        $sql = "SELECT * FROM preguntas WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function modificarPregunta($id, $categoria, $pregunta, $a, $b, $c, $d, $correcta)
    {
        $sql = "UPDATE preguntas 
            SET categoria_id = ?, pregunta = ?, opcion_a = ?, opcion_b = ?, opcion_c = ?, opcion_d = ?, correcta = ?
            WHERE id = ?";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param("issssssi",
            $categoria,
            $pregunta,
            $a,
            $b,
            $c,
            $d,
            $correcta,
            $id
        );

        return $stmt->execute();
    }


}