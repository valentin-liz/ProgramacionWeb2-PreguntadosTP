<?php

class ReportesModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getTodosLosReportes()
    {
        $sql = "SELECT r.id, r.mensaje, r.pregunta_id, p.pregunta 
            FROM reportes r
            JOIN preguntas p ON r.pregunta_id = p.id
            ORDER BY r.id";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }


}