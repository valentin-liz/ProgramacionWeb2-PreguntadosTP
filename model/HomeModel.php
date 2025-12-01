<?php

class HomeModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getPuntosUsuario($nombreUsuario) {

        $query = "SELECT puntos FROM usuarios WHERE usuario = '$nombreUsuario'";
        $resultado = $this->conexion->query($query);

        if ($fila = $resultado->fetch_assoc()) {
            return $fila['puntos'];
        } else {
            return NULL;
        }
    }

    public function getTodasLasPreguntas() {

        $query = "SELECT 
    p.id AS pregunta_id,
    p.categoria_id,
    c.nombre AS categoria,
    p.pregunta,
    p.opcion_a,
    p.opcion_b,
    p.opcion_c,
    p.opcion_d,
    p.correcta,
    p.veces_vista,
    p.veces_acertada,
    p.ratio,
    p.nivel
FROM preguntas p
JOIN categoria c ON p.categoria_id = c.id
ORDER BY p.id;
";
        $resultado = $this->conexion->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function borrarPregunta($id)
    {
        $sql = "DELETE FROM preguntas WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }


}