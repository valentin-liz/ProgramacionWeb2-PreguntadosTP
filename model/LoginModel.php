<?php

class LoginModel
{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getUserWith($user, $password)
    {
        // Busco el usuario por nombre
        $sql = "SELECT * FROM usuarios WHERE usuario = ?";
        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->conexion->error);
        }

        $stmt->bind_param("s", $user);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();

        $stmt->close();

        // Si no existe el usuario, retorno null
        if (!$usuario) {
            return null;
        }

        // Verifico si el password ingresado coincide con el hash guardado
        if (password_verify($password, $usuario["password"])) {
            return $usuario; // Login correcto
        } else {
            return null; // Contraseña incorrecta
        }
    }
}