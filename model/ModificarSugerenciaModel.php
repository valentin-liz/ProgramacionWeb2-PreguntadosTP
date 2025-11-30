<?php

class ModificarSugerenciaModel
{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getSugerenciaById($id)
    {
        $sql = "SELECT * FROM sugerencias WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getCategorias()
    {
        $sql = "SELECT id, nombre FROM categoria ORDER BY nombre ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function modificarSugerencia($id, $pregunta, $categoria, $a, $b, $c, $d, $correcta)
    {
        $sql = "UPDATE sugerencias
            SET pregunta = ?, categoria_id = ?, opcion_a = ?, opcion_b = ?, opcion_c = ?, opcion_d = ?, correcta = ?
            WHERE id = ?";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param("sisssssi",
            $pregunta,
            $categoria,
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