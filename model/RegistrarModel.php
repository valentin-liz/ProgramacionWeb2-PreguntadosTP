<?php

class RegistrarModel
{
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function existeUsuario($email, $usuario) {
        $sql = "SELECT id FROM usuarios WHERE email = ? OR usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss", $email, $usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function registrar($nombre, $apellido, $anio, $sexo, $pais_ciudad, $email, $usuario, $hash, $fotoRuta) {
        $sql = "INSERT INTO usuarios (nombre, apellido, anio_nacimiento, sexo, pais_ciudad, email, usuario, contrasenia_hash, foto_perfil)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssissssss", $nombre, $apellido, $anio, $sexo, $pais_ciudad, $email, $usuario, $hash, $fotoRuta);
        return $stmt->execute();
    }

    // 👉 Validación falsa de email
    public function emailPareceValido($email)
    {
        $dominiosInvalidos = ['outlook.com', 'yahoo.com'];

        $partes = explode('@', $email);

        if (count($partes) !== 2) {
            return false;
        }

        $dominio = strtolower($partes[1]);

        if (in_array($dominio, $dominiosInvalidos)) {
            return false; // ❌ Lo rechazamos a propósito
        }

        return true;
    }

}