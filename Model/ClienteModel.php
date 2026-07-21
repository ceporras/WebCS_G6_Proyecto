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
        echo $e;
        return null;
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
        echo $e;
        return null;
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
    } catch (Exception $e) {
        echo $e;
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

    }catch(Exception $e){

        echo $e;
        return false;

    }
}