<?php

date_default_timezone_set('UTC');

session_start();

include("helper/ConfigFactory.php");

$configFactory = new ConfigFactory();
$router = $configFactory->get("router");

$controller = isset($_GET["controller"]) ? ucfirst($_GET["controller"]) : "Login";
$method = isset($_GET["method"]) ? $_GET["method"] : "login";

// --- SISTEMA DE PERMISOS CENTRALIZADO ---

$accessControl = [

    "Login" => [
        "base" => ["public"],
        "login" => ["public"],
    ],

    "Registrar" => [
        "registrarUsuario" => ["public"],
        "redirectToLogin" => ["public"],
    ],

    "Home" => [
        "mostrarHome" => ["jugador", "editor", "administrador"],
        "borrarPregunta" => ["editor"],
    ],

    "Perfil" => [
        "mostrarPerfil" => ["jugador", "editor", "administrador"],
        "actualizarPerfil" => ["jugador"],
        "logout" => ["jugador", "editor", "administrador"],
    ],

    "Ranking" => [
        "mostrarRanking" => ["jugador"],
    ],

    "Partida" => [
        "iniciarPartida" => ["jugador"],
        "entregarPregunta" => ["jugador"],
        "responder" => ["jugador"],
        "ruleta" => ["jugador"],
        "salir" => ["jugador"],
        "resumen" => ["jugador"],
        "marcarAbandono" => ["jugador"],
    ],

    "SugerirPregunta" => [
        "mostrarSugerirPregunta" => ["jugador"],
        "guardarSugerenciaDePregunta" => ["jugador"],
    ],

    "AgregarPregunta" => [
        "mostrarAgregarPregunta" => ["editor"],
        "guardarPregunta" => ["editor"],
    ],

    "ModificarPregunta" => [
        "mostrarModificarPregunta" => ["editor"],
        "guardarModificacion" => ["editor"],
    ],

    "Reportes" => [
        "mostrarReportes" => ["editor"],
        "guardarModificacion" => ["editor"],
    ],

    "DetallesDeReporte" => [
        "mostrarDetallesDeReporte" => ["editor"],
        "finalizarReporte" => ["editor"],
    ],

    "Sugerencias" => [
        "mostrarSugerencias" => ["editor"],
        "agregarSugerencia" => ["editor"],
        "borrarSugerencia" => ["editor"],
    ],

    "ModificarSugerencia" => [
        "mostrarModificarSugerencia" => ["editor"],
        "guardarModificacion" => ["editor"],
    ],
];

// 1. Si el controlador no existe en el ACL - error 404
if (!isset($accessControl[$controller])) {
    die("404 - Controlador inexistente ($controller)");
}

// 2. Si el metodo no existe en el controllador - error 404
if (!isset($accessControl[$controller][$method])) {
    die("404 - Método inexistente ($method)");
}

// 3. Obtener roles permitidos
$allowedRoles = $accessControl[$controller][$method];

// 4. Si es público pasa
if (!in_array("public", $allowedRoles)) {

    // Si no hay sesión lleva a login
    if (!isset($_SESSION["rol"])) {
        header("Location: /");
        exit;
    }

    // Si el rol no coincide - 403
    if (!in_array($_SESSION["rol"], $allowedRoles)) {
        die("403 - No tenés permiso");
    }
}

$router->executeController($controller, $method);