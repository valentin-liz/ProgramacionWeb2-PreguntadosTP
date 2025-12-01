<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class RegistrarController
{
    private $model;
    public $renderer;

    public function __construct($model, $renderer)
    {
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function registrarUsuario()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $nombre = trim($_POST['nombre']);
            $apellido = trim($_POST['apellido']);
            $anio = $_POST['anio_nacimiento'] ?? null;
            $sexo = $_POST['sexo'] ?? null;
            $pais_ciudad = trim($_POST['pais_ciudad']);
            $email = trim($_POST['email']);
            $usuario = trim($_POST['usuario']);
            $contrasenia = $_POST['contrasenia'];

            // Campos obligatorios
            if (empty($nombre) || empty($apellido) || empty($email) || empty($usuario) || empty($contrasenia)) {
                $this->renderer->render("registrar", ["errorFaltanDatos" => "⚠️ Completá todos los campos obligatorios."]);
                return;
            }

            // Validación falsa de email
            if (!$this->model->emailPareceValido($email)) {
                $this->renderer->render("registrar", ["errorEmailFalso" => "❌ El email no es válido (validación falsa)."]);
                return;
            }

            // Usuario o email ya existen
            if ($this->model->existeUsuario($email, $usuario)) {
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

            // Hash de contraseña
            $hash = password_hash($contrasenia, PASSWORD_DEFAULT);

            // Registrar en la BD
            if ($this->model->registrar($nombre, $apellido, $anio, $sexo, $pais_ciudad, $email, $usuario, $hash, $fotoRuta)) {

                /* ==========================================
                   📧 ENVIAR EMAIL DE REGISTRO
                ========================================== */

                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host = "smtp.hostinger.com";
                    $mail->SMTPAuth = true;

                    $mail->Username = "programacionweb2@openpathweb.com";
                    $mail->Password = "ProgramacionWeb2!";

                    // Usamos SSL en puerto 465 (más estable en Hostinger)
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port = 465;

                    $mail->CharSet = "UTF-8";

                    $mail->setFrom("programacionweb2@openpathweb.com", "Preguntados");
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = "Registro exitoso en Preguntados";

                    $mail->Body = "
        <h2>¡Bienvenido/a a Preguntados!</h2>
        <p>Se registró una cuenta con este correo: <b>$email</b>.</p>
        <p><b>Usuario:</b> $usuario</p>
        <p>Si no realizaste este registro, ignorá este mensaje.</p>
        <br>
        <p>Equipo de Preguntados 🎉</p>
    ";

                    $mail->send();
                } catch (Exception $e) {
                    error_log("❌ Error al enviar mail: " . $mail->ErrorInfo);
                }

                // Mostrar mensaje
                $this->renderer->render("registrar", ["exito" => "✅ Usuario registrado correctamente."]);

                // Redirigir
                $this->redirectToLogin();
            } else {
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
