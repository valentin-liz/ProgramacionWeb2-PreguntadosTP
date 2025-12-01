<?php

class ModificarCategoriaModel
{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getCategoriaById($id)
    {
        $sql = "SELECT * FROM categoria WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function modificarCategoria($id, $nombre, $color)
    {
        $sql = "UPDATE categoria 
            SET nombre = ?, color = ?
            WHERE id = ?";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param("ssi",
            $nombre,
            $color,
            $id
        );

        return $stmt->execute();
    }

}