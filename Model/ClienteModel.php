<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/Model/UtilModel.php';

function IniciarSesionModel($correo, $contrasenna)
{
    try {
        $conn = OpenDB();

        $sql = "CALL spIniciarSesionCliente('$correo','$contrasenna')";
        $response = $conn->query($sql);

        // Se guarda el resultado en una variable nueva
        $datos = null;

        while ($fila = $response->fetch_assoc()) {
            $datos = $fila;
        }

        CloseDB($conn);
        return $datos;
    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "IniciarSesionModel", $e);
        return false;
    }
}

function ValidarCorreoModel($correo)
{
    try {
        $conn = OpenDB();

        $sql = "CALL spValidarCorreoCliente('$correo')";
        $response = $conn->query($sql);

        $datos = null;

        while ($fila = $response->fetch_assoc()) {
            $datos = $fila;
        }

        CloseDB($conn);
        return $datos;
    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "ValidarCorreoModel", $e);
        return false;
    }
}

function ActualizarContrasennaModel($idCliente, $contrasenna)
{
    try {
        $conn = OpenDB();

        $sql = "CALL spActualizarContrasennaCliente(
                    '$idCliente',
                    '$contrasenna'
                )";

        $response = $conn->query($sql);

        CloseDB($conn);
        return $response;
    }  catch (Exception $e) {
        addLog(timestamp(), "ERROR", "ActualizarContrasennaModel", $e);
        return false;
    }
}

function RegistrarUsuarioModel(
    $nombre,
    $apellidoPaterno,
    $apellidoMaterno,
    $correo,
    $telefono,
    $password
)
{
    try{

        $conn = OpenDB();

        $sql = "CALL spRegisterCliente(
                    '$nombre',
                    '$apellidoPaterno',
                    '$apellidoMaterno',
                    '$correo',
                    '$telefono',
                    '$password'
                )";

        $response = $conn->query($sql);

        CloseDB($conn);

        return $response;

    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "RegistrarUsuarioModel", $e);
        return false;
    }
}