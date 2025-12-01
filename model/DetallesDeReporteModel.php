<?php

class DetallesDeReporteModel
{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getReporteById($id)
    {
        $sql = "
        SELECT
            r.id AS reporte_id,
            r.mensaje,
            r.pregunta_id,

            p.id AS pregunta_id,
            p.pregunta,
            p.categoria_id,
            c.nombre AS categoria,
            p.opcion_a,
            p.opcion_b,
            p.opcion_c,
            p.opcion_d,
            p.correcta,
            p.veces_vista,
            p.veces_acertada,
            p.ratio,
            p.nivel
        FROM reportes r
        JOIN preguntas p ON r.pregunta_id = p.id
        JOIN categoria c ON p.categoria_id = c.id
        WHERE r.id = ?
        LIMIT 1
    ";

        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar consulta: " . $this->conexion->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();

        return $row ?: null;
    }

    public function borrarReporte($id)
    {
        $sql = "DELETE FROM reportes WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }



}