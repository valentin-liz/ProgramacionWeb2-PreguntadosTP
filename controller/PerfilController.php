<?php
include_once("model/UsuarioModel.php");
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

        $this->renderer->render("perfil", [
            "logueado" => true,
            "usuario" => $datos
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
            $ok = $this->model->actualizarPerfil($datos, $usuario);

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
}
