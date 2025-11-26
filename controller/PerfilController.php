<?php
include_once("model/PerfilModel.php");
include_once("helper/AuthHelper.php");

class PerfilController
{
    private $model;
    private $renderer;

    public function __construct($model, $renderer)
    {
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function mostrarPerfil()
    {
        AuthHelper::checkLogin();

        $usuario = $_SESSION['usuario'];

        $datos = $this->model->obtenerUsuario($usuario);
        $stats = $this->model->obtenerStats($usuario);

        // Marcar sexo
        $datos["is_masculino"] = ($datos["sexo"] === "Masculino");
        $datos["is_femenino"]  = ($datos["sexo"] === "Femenino");
        $datos["is_otro"]      = ($datos["sexo"] === "Prefiero no cargarlo");

        // Marcar el sexo seleccionado para Mustache
        $datos["is_masculino"] = ($datos["sexo"] === "Masculino");
        $datos["is_femenino"]  = ($datos["sexo"] === "Femenino");
        $datos["is_otro"]      = ($datos["sexo"] === "Otro");

        $this->renderer->render("perfil", [
            "usuario" => $datos,
            "stats" => $stats,
            "logueado" => true
        ]);
    }

    public function actualizarPerfil()
    {
        AuthHelper::checkLogin();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario = $_SESSION['usuario'];

            // Validar que existan los campos esperados
            $datos = [
                "nombre" => $_POST["nombre"] ?? '',
                "apellido" => $_POST["apellido"] ?? '',
                "anio_nacimiento" => $_POST["anio_nacimiento"] ?? '',
                "sexo" => $_POST["sexo"] ?? '',
                "pais_ciudad" => $_POST["pais_ciudad"] ?? '',
                "email" => $_POST["email"] ?? ''
            ];

            // Ejecutar actualización
            $ok = $this->model->actualizarPerfil($datos, $usuario, $_FILES);

            if ($ok) {
                // Si usás rutas limpias (htaccess)
                header("Location: /perfil/mostrarPerfil");
                exit;
            } else {
                echo "<p style='color:red; text-align:center;'>❌ Error al guardar los cambios.</p>";
            }
        } else {
            // Si no se accedió por POST, redirigir
            header("Location: /perfil/mostrarPerfil");
            exit;
        }
    }

    // Método opcional para que /perfil funcione directamente
    public function base()
    {
        $this->mostrarPerfil();
    }

    public function logout() {
        session_start();
        session_unset();     // Limpia todas las variables $_SESSION
        session_destroy();   // Destruye la sesión en el servidor

        header("Location: /login/login");
        exit;
    }

}
