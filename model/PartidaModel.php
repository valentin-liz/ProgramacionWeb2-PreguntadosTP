<?php

class PartidaModel
{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getCategorias() {
        $query = "SELECT nombre, color FROM categoria";
        $resultado = $this->conexion->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }


}