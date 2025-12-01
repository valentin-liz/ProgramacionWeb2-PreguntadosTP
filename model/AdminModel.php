<?php

class AdminModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    /* ================================
       Rango de fechas (solo para usuarios nuevos)
    =================================*/
    public function calcularRangoFechas(string $tipo, ?string $fechaBase = null): array
    {
        $base = $fechaBase ? new DateTime($fechaBase) : new DateTime();

        switch ($tipo) {
            case 'dia':
                $d1 = (clone $base)->setTime(0, 0);
                $d2 = (clone $base)->setTime(23, 59, 59);
                break;
            case 'semana':
                $d1 = (clone $base)->modify("monday this week")->setTime(0, 0);
                $d2 = (clone $base)->modify("sunday this week")->setTime(23, 59, 59);
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

    /* ================================
       Métricas simples
    =================================*/

    public function getCantidadUsuarios()
    {
        $sql = "SELECT COUNT(*) AS total FROM usuarios";
        $res = $this->conexion->query($sql)->fetch_assoc();
        return (int)$res["total"];
    }

    public function getCantidadPartidas()
    {
        $sql = "SELECT COUNT(*) AS total FROM partida";
        $res = $this->conexion->query($sql)->fetch_assoc();
        return (int)$res["total"];
    }

    public function getCantidadPreguntasJuego()
    {
        $sql = "SELECT COUNT(*) AS total FROM preguntas";
        $res = $this->conexion->query($sql)->fetch_assoc();
        return (int)$res["total"];
    }

    public function getCantidadPreguntasCreadas()
    {
        $sql = "SELECT COUNT(*) AS total FROM sugerencias";
        $res = $this->conexion->query($sql)->fetch_assoc();
        return (int)$res["total"];
    }

    public function getCantidadUsuariosNuevos($desde, $hasta)
    {
        $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE creado_en BETWEEN ? AND ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss", $desde, $hasta);
        $stmt->execute();
        return (int)$stmt->get_result()->fetch_assoc()["total"];
    }

    /* ================================
       ACIERTOS POR USUARIO
    =================================*/

    public function getPorcentajeAciertosPorUsuario()
    {
        $sql = "
        SELECT 
            u.usuario,
            COUNT(pr.id) AS total_respuestas,
            SUM(CASE WHEN p.id IS NOT NULL 
                      AND p.correcta = (
                            SELECT correcta
                            FROM preguntas
                            WHERE id = pr.pregunta_id
                      ) THEN 1 ELSE 0 END) AS correctas,
            ROUND(
                CASE 
                    WHEN COUNT(pr.id) = 0 THEN 0
                    ELSE (
                        SUM(CASE WHEN p.id IS NOT NULL 
                                  AND p.correcta = (
                                        SELECT correcta
                                        FROM preguntas
                                        WHERE id = pr.pregunta_id
                                  ) THEN 1 ELSE 0 END)
                        / COUNT(pr.id)
                    ) * 100
                END, 2
            ) AS porcentaje
        FROM usuarios u
        LEFT JOIN preguntas_respondidas pr
            ON u.id = pr.usuario_id
        LEFT JOIN preguntas p
            ON pr.pregunta_id = p.id
        GROUP BY u.id
        ORDER BY porcentaje DESC
    ";

        $res = $this->conexion->query($sql);
        return $res->fetch_all(MYSQLI_ASSOC);
    }


    /* ================================
       USUARIOS POR SEXO
    =================================*/
    public function getUsuariosPorSexo()
    {
        $sql = "SELECT sexo, COUNT(*) AS cantidad
                FROM usuarios
                GROUP BY sexo";

        $res = $this->conexion->query($sql);
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    /* ================================
       USUARIOS POR GRUPO DE EDAD
    =================================*/
    public function getUsuariosPorGrupoEdad()
    {
        $sql = "SELECT
                    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, CONCAT(anio_nacimiento,'-01-01'),CURDATE()) < 18 THEN 1 END) AS menores,
                    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, CONCAT(anio_nacimiento,'-01-01'),CURDATE()) BETWEEN 18 AND 65 THEN 1 END) AS medio,
                    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, CONCAT(anio_nacimiento,'-01-01'),CURDATE()) > 65 THEN 1 END) AS jubilados
                FROM usuarios";

        $res = $this->conexion->query($sql);
        return $res->fetch_assoc();
    }

    /* ================================
   USUARIOS POR PAÍS
================================*/
    public function getUsuariosPorPais()
    {
        $sql = "SELECT 
                IFNULL(pais_ciudad,'Sin especificar') AS pais_ciudad,
                COUNT(*) AS cantidad
            FROM usuarios
            GROUP BY pais_ciudad";

        $res = $this->conexion->query($sql);
        return $res->fetch_all(MYSQLI_ASSOC);
    }
}
