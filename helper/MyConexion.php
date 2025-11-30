<?php

class MyConexion
{

    private $conexion;

    public function __construct($server, $user, $pass, $database, $port)
    {


        $this->conexion = new mysqli($server, $user, $pass, $database, $port);
        if ($this->conexion->error) { die("Error en la conexión: " . $this->conexion->error); }
        $this->conexion->set_charset("utf8mb4");
        $this->conexion->query("SET time_zone = '-03:00'");
    }

    public function query($sql)
    {
        $result = $this->conexion->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return null;
    }

    public function getConexion()
    {
        return $this->conexion;
    }
}