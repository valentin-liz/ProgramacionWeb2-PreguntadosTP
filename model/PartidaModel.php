<?php

class PartidaModel
{

    private $conexion;

    public function getConexion()
    {
        return $this->conexion;
    }


    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getCategorias()
    {
        $query = "SELECT nombre, color FROM categoria";
        $resultado = $this->conexion->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function getPreguntaPorCategoria($categoria)
    {
        $sql = "SELECT * FROM preguntas WHERE categoria = ? ORDER BY RAND() LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $categoria);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }
}
