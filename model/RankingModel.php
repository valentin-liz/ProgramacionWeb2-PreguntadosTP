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
            END AS ratio,
            CASE
                WHEN puntos < 10 THEN 1
                WHEN puntos < 30 THEN 2
                WHEN puntos < 60 THEN 3
                WHEN puntos < 100 THEN 4
                ELSE 5
            END AS nivel
        FROM usuarios
        ORDER BY ratio DESC;
        ";

        $resultado = $this->conexion->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

}
