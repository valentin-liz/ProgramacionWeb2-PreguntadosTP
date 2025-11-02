<?php
include_once("helper/MyConexion.php");
include_once("helper/IncludeFileRenderer.php");
include_once("helper/NewRouter.php");
include_once("controller/LoginController.php");
include_once("controller/HomeController.php");
include_once("controller/PartidaController.php");
include_once("model/LoginModel.php");
include_once("model/HomeModel.php");
require_once __DIR__ . '/../vendor/autoload.php';
include_once ("helper/MustacheRenderer.php");

class ConfigFactory
{
    private $config;
    private $objetos;

    private $conexion;
    private $renderer;

    public function __construct()
    {
        $this->config = parse_ini_file("config/config.ini");

        $this->conexion= new MyConexion(
            $this->config["server"],
            $this->config["user"],
            $this->config["pass"],
            $this->config["database"],
            $this->config["port"]
        );

        $this->renderer = new MustacheRenderer("vista");

        $this->objetos["router"] = new NewRouter($this, "LoginController", "base");

        $this->objetos["LoginController"] = new LoginController(new LoginModel($this->conexion->getConexion()), $this->renderer);

        $this->objetos["HomeController"] = new HomeController(new HomeModel($this->conexion->getConexion()), $this->renderer);

        $this->objetos["PartidaController"] = new PartidaController($this->renderer);

    }

    public function get($objectName)
    {
        return $this->objetos[$objectName];
    }
}