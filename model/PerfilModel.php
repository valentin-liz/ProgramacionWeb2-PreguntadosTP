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
}
