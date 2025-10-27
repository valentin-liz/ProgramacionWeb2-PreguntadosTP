<?php

class MyConexion
{

    private $conexion;

    public function __construct($server, $user, $pass, $database, $port = 3306)
    {
        $this->conexion = new mysqli($server, $user, $pass, $database, $port);
        if ($this->conexion->error) { die("Error en la conexión: " . $this->conexion->error); }
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