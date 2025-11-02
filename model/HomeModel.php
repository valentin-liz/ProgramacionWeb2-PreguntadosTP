<?php

class HomeModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getPuntosUsuario($nombreUsuario) {

        $query = "SELECT puntos FROM usuarios WHERE usuario = '$nombreUsuario'";
        $resultado = $this->conexion->query($query);

        if ($fila = $resultado->fetch_assoc()) {
            return $fila['puntos'];
        } else {
            return NULL;
        }
    }

}