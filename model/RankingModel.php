<?php

class RankingModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerUsuariosOrdenadosPorRatio() {

        $query = "SELECT 
            id,
            usuario,
            hits,
            vistos,
            partidas_jugadas,
            foto_perfil,
            ratio,
            nivel
        FROM usuarios
        WHERE rol = 'jugador'
        ORDER BY ratio DESC;
        ";

        $resultado = $this->conexion->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

}
