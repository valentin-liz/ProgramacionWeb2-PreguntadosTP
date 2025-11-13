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
    puntos,
    partidas_jugadas,
    foto_perfil,
    CASE 
        WHEN partidas_jugadas > 0 THEN puntos / partidas_jugadas
        ELSE 0
    END AS ratio
FROM usuarios
ORDER BY ratio DESC;
";
        $resultado = $this->conexion->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }


}