<?php

class RegistrarController
{
    private $model;
    public $renderer;

    public function __construct($model, $renderer) {
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function registrarUsuario() {

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $nombre = trim($_POST['nombre']);
            $apellido = trim($_POST['apellido']);
            $anio = $_POST['anio_nacimiento'] ?? null;
            $sexo = $_POST['sexo'] ?? null;
            $pais_ciudad = trim($_POST['pais_ciudad']);
            $email = trim($_POST['email']);
            $usuario = trim($_POST['usuario']);
            $contrasenia = $_POST['contrasenia'];

            if (empty($nombre) || empty($apellido) || empty($email) || empty($usuario) || empty($contrasenia)) {
//                $this->msg = "<p style='color:red;'>⚠️ Completá todos los campos obligatorios.</p>";
                $this->renderer->render("registrar", ["errorFaltanDatos" => "⚠️ Completá todos los campos obligatorios."]);
                return;
            }

            if (!$this->model->emailPareceValido($email)) {
                $this->renderer->render("registrar", [
                    "errorEmailFalso" => "❌ El email no es válido (validación falsa)."
                ]);
                return;
            }            

            if ($this->model->existeUsuario($email, $usuario)) {
//                $this->msg = "<p style='color:red;'>❌ El email o el usuario ya existen.</p>";
                $this->renderer->render("registrar", ["errorUsuarioYaExiste" => "❌ El email o el usuario ya existen."]);
                return;
            }

            // Foto de perfil
            $fotoRuta = null;

            if (!empty($_FILES['foto_perfil']['name'])) {

                $carpeta = "public/imagenes/perfilesImgs/";

                if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);

                $ext = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
                $nuevoNombre = uniqid() . "." . $ext;
                $destino = $carpeta . $nuevoNombre;

                if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $destino)) {
                    $fotoRuta = $destino;
                }
            }

            // Hash
            $hash = password_hash($contrasenia, PASSWORD_DEFAULT);

            if ($this->model->registrar($nombre, $apellido, $anio, $sexo, $pais_ciudad, $email, $usuario, $hash, $fotoRuta)) {
//                $this->msg = "<p style='color:green;'>✅ Usuario registrado correctamente. <a href='login.php'>Iniciar sesión</a></p>";
                $this->renderer->render("registrar", ["exito" => "✅ Usuario registrado correctamente."]);
                $this->redirectToLogin();


            } else {
//                $this->msg = "<p style='color:red;'>❌ Error al registrar usuario.</p>";
                $this->renderer->render("registrar", ["errorAlRegistrar" => "❌ Error al registrar usuario."]);
            }

        } else {

            $this->renderer->render("registrar");
        }
    }

    private function redirectToLogin()
    {
        header("Location: /login/login");
        exit;
    }

}