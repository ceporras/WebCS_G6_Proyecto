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
    } catch (Exception $e) {
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
) {
    try {

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


function GetClientes()
{
    try {
        $conn = OpenDB();
        $sql = "CALL sp_GetClientes()";
        $response = $conn->query($sql);

        $datos = [];
        while ($fila = $response->fetch_assoc()) {
            $datos[] = $fila;
        }

        CloseDB($conn);
        return $datos;
    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "GetClientes", $e);
    }
}



function GetClienteById($ID_Cliente)
{
    try {
        $conn = OpenDB();
        $sql = "CALL sp_GetCliente_By_ID('$ID_Cliente')";
        $response = $conn->query($sql);

        $datos = null;
        while ($fila = $response->fetch_assoc()) {
            $datos = $fila;
        }

        CloseDB($conn);
        return $datos;
    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "GetClienteById", $e);
    }
}

function GetClientePwdById($ID_Cliente)
{
    try {
        $conn = OpenDB();
        $sql = "CALL sp_GetClientePwd_By_ID('$ID_Cliente')";
        $response = $conn->query($sql);

        $datos = null;
        while ($fila = $response->fetch_assoc()) {
            $datos = $fila;
        }

        CloseDB($conn);
        return $datos;
    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "GetClienteById", $e);
    }
}

function UpdateCliente($ID_Cliente, $Nombre, $ApellidoPaterno, $ApellidoMaterno, $Correo, $Telefono, $Estado, $Rol) {
    try {

        $conn = OpenDB();

        $sql = "CALL sp_UpdateCliente(
            '$ID_Cliente',
            '$Nombre',
            '$ApellidoPaterno',
            '$ApellidoMaterno',
            '$Correo',
            '$Telefono',
            '$Estado',
            '$Rol'
        )";

        $response = $conn->query($sql);

        CloseDB($conn);

        return $response;
    } catch (Exception $e) {

        addLog(timestamp(), "ERROR", "UpdateCliente", $e);

        return false;
    }
}

function DeleteCliente($ID_Cliente)
{
    try {
        $conn = OpenDB();

        $sql = "CALL sp_DeleteCliente('$ID_Cliente')";
        $response = $conn->query($sql);

        CloseDB($conn);

        return $response;
    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "DeleteClienteById", $e);
        return false;
    }
}

function CrearCliente($Nombre, $ApellidoPaterno, $ApellidoMaterno, $Correo, $Telefono, $Estado, $ID_Rol, $Password) {
    try {

        $conn = OpenDB();

        $sql = "CALL sp_CreateCliente(
            '$Nombre',
            '$ApellidoPaterno',
            '$ApellidoMaterno',
            '$Correo',
            '$Telefono',
            '$Estado',
            '$ID_Rol',
            '$Password'
        )";

        $conn->query($sql);

        CloseDB($conn);

        return [
            "success" => true
        ];
    } catch (mysqli_sql_exception $e) {

        addLog(timestamp(), "ERROR", "CrearCliente", $e);

        return [
            "success" => false,
            "code" => $e->getCode(),
            "message" => $e->getMessage()
        ];
    }
}


function UpdateClientePerfil(
    $ID_Cliente,
    $Nombre,
    $ApellidoPaterno,
    $ApellidoMaterno,
    $Correo,
    $Telefono
)
{
    try {

        $conn = OpenDB();

        $sql = "CALL sp_UpdateClientePerfil(
            '$ID_Cliente',
            '$Nombre',
            '$ApellidoPaterno',
            '$ApellidoMaterno',
            '$Correo',
            '$Telefono'
        )";

        $conn->query($sql);

        CloseDB($conn);

        return [
            "success" => true,
            "message" => "Perfil actualizado correctamente."
        ];

    } catch (mysqli_sql_exception $e) {

        addLog(
            timestamp(),
            "ERROR",
            "UpdateClientePerfil",
            $e
        );

        if ($e->getCode() == 1062) {

            return [
                "success" => false,
                "message" => "Ya existe un usuario con ese correo electrónico."
            ];
        }

        return [
            "success" => false,
            "message" => "No se pudo actualizar el perfil."
        ];
    }
}


function UpdatePassword($ID_Cliente, $Password)
{
    try {

        $conn = OpenDB();

        $sql = "CALL sp_UpdateClientePassword(
            '$ID_Cliente',
            '$Password'
        )";

        $conn->query($sql);

        CloseDB($conn);

        return [
            "success" => true,
            "message" => "Contraseña actualizada correctamente."
        ];

    } catch (Exception $e) {

        addLog(
            timestamp(),
            "ERROR",
            "UpdatePassword",
            $e
        );

        return [
            "success" => false,
            "message" => "No se pudo actualizar la contraseña."
        ];
    }
}