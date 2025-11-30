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


    /** 🔥 ACTUALIZAR PERFIL CON FOTO DE PERFIL OPCIONAL  */
    public function actualizarPerfil($datos, $usuario, $archivo)
    {
        $rutaFoto = null;

        // Si se subió una imagen nueva
        if ($archivo["foto_perfil"]["error"] === UPLOAD_ERR_OK) {

            $nombreTmp = $archivo["foto_perfil"]["tmp_name"];
            $nombreFinal = uniqid() . "_" . basename($archivo["foto_perfil"]["name"]);

            // Carpeta donde guardar la foto
            $rutaCarpeta = "public/imagenes/perfilesImgs/";
            $rutaCompleta = $rutaCarpeta . $nombreFinal;

            // Mover archivo
            if (move_uploaded_file($nombreTmp, $rutaCompleta)) {
                $rutaFoto = $rutaCompleta;
            }
        }

        // Si NO hay foto nueva → no actualizamos foto_perfil
        if ($rutaFoto) {
            $sql = "UPDATE usuarios
                    SET nombre = ?, apellido = ?, anio_nacimiento = ?, sexo = ?, pais_ciudad = ?, email = ?, foto_perfil = ?
                    WHERE usuario = ?";

            $stmt = $this->db->prepare($sql);

            $stmt->bind_param(
                "ssssssss",
                $datos["nombre"],
                $datos["apellido"],
                $datos["anio_nacimiento"],
                $datos["sexo"],
                $datos["pais_ciudad"],
                $datos["email"],
                $rutaFoto,
                $usuario
            );

        } else {
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
        }

        return $stmt->execute();
    }

    public function obtenerStats($usuarioId)
    {
        /*
        1) Traer puntos_totales y nivel del usuario
        2) Traer cantidad de partidas jugadas
        3) Calcular promedio (puntos / partidas) — si partidas = 0, devolver 0
        4) Traer nivel
    */

        // 1. Puntos totales y nivel
        $sql = "SELECT puntos, nivel FROM usuarios WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $usuario = $stmt->get_result()->fetch_assoc();

        $puntosTotales = $usuario["puntos"] ?? 0;
        $nivel = $usuario["nivel"] ?? "nuevo";


        // 2. Cantidad de partidas jugadas (desde la tabla usuarios)
        $sql = "SELECT partidas_jugadas FROM usuarios WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        $partidasJugadas = $result["partidas_jugadas"] ?? 0;


        // 3. Calcular promedio
        if ($partidasJugadas > 0) {
            $promedioPartidasYPuntos = $puntosTotales / $partidasJugadas;
        } else {
            $promedioPartidasYPuntos = 0;
        }


        return [
            "puntos" => $puntosTotales,
            "partidas" => $partidasJugadas,
            "promedioPartidasYPuntos" => $promedioPartidasYPuntos,
            "nivel" => $nivel
        ];
    }
}
