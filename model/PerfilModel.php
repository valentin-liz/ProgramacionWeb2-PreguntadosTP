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
}
