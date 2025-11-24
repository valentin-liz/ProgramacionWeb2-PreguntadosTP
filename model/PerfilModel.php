<?php

class PerfilModel
{
    private $db;

    public function __construct($conexion)
    {
        $this->db = $conexion;
    }

    public function obtenerUsuario($usuario)
    {
        $sql = "SELECT nombre, apellido, anio_nacimiento, sexo, pais_ciudad, email, usuario, foto_perfil
                FROM usuarios
                WHERE usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function actualizarPerfil($datos, $usuario)
    {
        $sql = "UPDATE usuarios
                SET nombre = ?, apellido = ?, anio_nacimiento = ?, sexo = ?, pais_ciudad = ?, email = ?
                WHERE usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "sssssss",
            $datos["nombre"],
            $datos["apellido"],
            $datos["anio_nacimiento"],
            $datos["sexo"],
            $datos["pais_ciudad"],
            $datos["email"],
            $usuario
        );
        return $stmt->execute();
    }

    public function obtenerStats($usuario)
    {

        // Total de partidas jugadas
        $sqlTotal = "SELECT COUNT(*) AS total FROM partidas_usuario WHERE usuario = ?";
        $stmt1 = $this->db->prepare($sqlTotal);
        $stmt1->bind_param("s", $usuario);
        $stmt1->execute();
        $total = $stmt1->get_result()->fetch_assoc()["total"] ?? 0;

        // Respuestas correctas
        $sqlCorrectas = "SELECT COUNT(*) AS correctas 
                     FROM partidas_usuario 
                     WHERE usuario = ? AND respondida_correcta = 1";
        $stmt2 = $this->db->prepare($sqlCorrectas);
        $stmt2->bind_param("s", $usuario);
        $stmt2->execute();
        $correctas = $stmt2->get_result()->fetch_assoc()["correctas"] ?? 0;

        // Ratio
        $ratio = $total > 0 ? round($correctas / $total, 2) : 0;

        // Nivel calculado (puede ajustarse)
        $nivel = floor($ratio * 10);

        // Puntos desde usuarios
        $sqlPuntos = "SELECT puntos FROM usuarios WHERE usuario = ?";
        $stmt3 = $this->db->prepare($sqlPuntos);
        $stmt3->bind_param("s", $usuario);
        $stmt3->execute();
        $puntos = $stmt3->get_result()->fetch_assoc()["puntos"] ?? 0;

        return [
            "puntos" => $puntos,
            "partidas" => $total,
            "ratio" => $ratio,
            "nivel" => $nivel
        ];
    }
}
