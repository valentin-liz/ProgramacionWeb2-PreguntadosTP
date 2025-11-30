<?php

class AdminModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function calcularRangoFechas(string $tipo, ?string $fechaBase = null): array
    {
        $base = $fechaBase ? new DateTime($fechaBase) : new DateTime();

        switch ($tipo) {
            case 'dia':
                $d1 = (clone $base)->setTime(0,0);
                $d2 = (clone $base)->setTime(23,59,59);
                break;
            case 'semana':
                $d1 = (clone $base)->modify("monday this week")->setTime(0,0);
                $d2 = (clone $base)->modify("sunday this week")->setTime(23,59,59);
                break;
            case 'mes':
                $d1 = new DateTime($base->format("Y-m-01 00:00:00"));
                $d2 = new DateTime($base->format("Y-m-t 23:59:59"));
                break;
            case 'anio':
                $d1 = new DateTime($base->format("Y-01-01 00:00:00"));
                $d2 = new DateTime($base->format("Y-12-31 23:59:59"));
                break;
            default:
                $d1 = new DateTime("1970-01-01 00:00:00");
                $d2 = new DateTime();
                break;
        }

        return [
            $d1->format("Y-m-d H:i:s"),
            $d2->format("Y-m-d H:i:s")
        ];
    }

    public function getCantidadUsuarios($desde,$hasta)
    {
        $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE creado_en BETWEEN ? AND ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss",$desde,$hasta);
        $stmt->execute();
        return (int)$stmt->get_result()->fetch_assoc()["total"];
    }

    public function getCantidadPartidas($desde,$hasta)
    {
        $sql = "SELECT SUM(partidas_jugadas) AS total FROM usuarios WHERE creado_en BETWEEN ? AND ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss",$desde,$hasta);
        $stmt->execute();
        return (int)$stmt->get_result()->fetch_assoc()["total"];
    }

    public function getCantidadPreguntasJuego($desde,$hasta)
    {
        $sql = "SELECT COUNT(*) AS total FROM preguntas WHERE creada_en BETWEEN ? AND ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss",$desde,$hasta);
        $stmt->execute();
        return (int)$stmt->get_result()->fetch_assoc()["total"];
    }

    public function getCantidadPreguntasCreadas($desde,$hasta)
    {
        $sql = "SELECT COUNT(*) AS total FROM sugerencias WHERE creada_en BETWEEN ? AND ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss",$desde,$hasta);
        $stmt->execute();
        return (int)$stmt->get_result()->fetch_assoc()["total"];
    }

    public function getCantidadUsuariosNuevos($desde,$hasta)
    {
        return $this->getCantidadUsuarios($desde,$hasta);
    }

    public function getPorcentajeAciertosPorUsuario($desde,$hasta)
    {
        $sql = "SELECT 
                    u.usuario,
                    COUNT(pu.id) AS total_respuestas,
                    SUM(pu.respondida_correcta) AS correctas,
                    ROUND(
                        CASE 
                            WHEN COUNT(pu.id)=0 THEN 0
                            ELSE (SUM(pu.respondida_correcta)/COUNT(pu.id))*100
                        END, 2
                    ) AS porcentaje
                FROM usuarios u
                LEFT JOIN partidas_usuario pu
                    ON u.usuario = pu.usuario
                    AND pu.fecha BETWEEN ? AND ?
                GROUP BY u.usuario
                ORDER BY porcentaje DESC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss",$desde,$hasta);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getUsuariosPorPais($desde,$hasta)
    {
        $sql = "SELECT 
                    IFNULL(pais_ciudad,'Sin especificar') AS pais_ciudad,
                    COUNT(*) AS cantidad
                FROM usuarios
                WHERE creado_en BETWEEN ? AND ?
                GROUP BY pais_ciudad";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss",$desde,$hasta);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getUsuariosPorSexo($desde,$hasta)
    {
        $sql = "SELECT sexo, COUNT(*) AS cantidad
                FROM usuarios
                WHERE creado_en BETWEEN ? AND ?
                GROUP BY sexo";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss",$desde,$hasta);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getUsuariosPorGrupoEdad($desde,$hasta)
    {
        $sql = "SELECT
                    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, CONCAT(anio_nacimiento,'-01-01'),CURDATE())<18 THEN 1 END) AS menores,
                    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, CONCAT(anio_nacimiento,'-01-01'),CURDATE()) BETWEEN 18 AND 65 THEN 1 END) AS medio,
                    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, CONCAT(anio_nacimiento,'-01-01'),CURDATE())>65 THEN 1 END) AS jubilados
                FROM usuarios
                WHERE creado_en BETWEEN ? AND ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss",$desde,$hasta);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
