<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/Model/UtilModel.php';

function IniciarSesionModel($correoElectronico, $contrasenna)
{
    try {
        $conn = OpenDB();

        $sql = "CALL spIniciarSesionCliente('$correoElectronico','$contrasenna')";
        $response = $conn->query($sql);

        //Se guarda el resultado en una variable nueva
        $datos = null;
        while ($fila = $response->fetch_assoc()) {
            $datos = $fila;
            echo "$datos";
        }

        CloseDB($conn);
        return $datos;
    } catch (Exception $e) {
        echo "$e";
        return null;
    }
}

function RegistrarUsuarioModel() {}

function ActualizarContrasennaModel() {}
