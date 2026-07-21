<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/Model/ClienteModel.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (isset($_POST["btnIniciarSesion"])) {
    $correo = $_POST["correo"];
    $contrasenna = $_POST["contrasenna"];

    $datos = IniciarSesionModel($correo, $contrasenna);

    if ($datos) {
        //var_dump($datos);
        $_SESSION["ID_Cliente"] = $datos["ID_Cliente"];        
        $_SESSION["Nombre"] = $datos["Nombre"] . " " . $datos["ApellidoPaterno"];
        

        header("Location: ../View/index.php");
        exit();
    }

    $_POST["Mensaje"] = "No se ha podido autenticar su información correctamente";
}


if (isset($_POST["btnSalir"])) {
    CerrarSesion();
}


function CerrarSesion()
    {
        session_start();
        session_destroy();
        header("Location: ../../View/vInicio/IniciarSesion.php");
        exit();
    }