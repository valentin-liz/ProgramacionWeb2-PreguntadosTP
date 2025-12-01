<?php

class AgregarCategoriaModel
{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function agregarCategoria($nombre, $color)
    {
        $sql = "INSERT INTO categoria 
            (nombre, color) 
            VALUES (?, ?)";

        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
        }

        $stmt->bind_param("ss",
            $nombre,
            $color
        );

        return $stmt->execute();
    }

}