<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/Model/UtilModel.php';

function AddOrden($ID_Cliente, $ID_Promocion, $Estado, $cantidadEntradas, $Subtotal, $Descuento, $Total)
{
    try {
        $conn = OpenDB();
        $sql = "CALL spAddOrden('$ID_Cliente', '$ID_Promocion', '$Estado', '$cantidadEntradas', '$Subtotal', '$Descuento', '$Total')";
        $result = $conn->query($sql);

        $row = $result->fetch_assoc();
        $ID_Orden = $row["ID_Orden"];

        CloseDB($conn);
        return $ID_Orden;
    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "AddOrden", $e);
        return false;
    }
}

function AddBoleto($ID_Orden, $ID_Funcion, $ID_Asiento, $TipoBoleto)
{
    try {
        $conn = OpenDB();
        $sql = "CALL spAddBoleto('$ID_Orden', '$ID_Funcion', '$ID_Asiento', '$TipoBoleto')";
        $response = $conn->query($sql);

        CloseDB($conn);
        return $response;
    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "AddBoleto", $e);
        return false;
    }
}

function GetBoletosByOrden($ID_Orden)
{
    try {
        $conn = OpenDB();
        $sql = "CALL sp_GetBoletosByOrden('$ID_Orden')";
        $result = mysqli_query($conn, $sql);
        CloseDB($conn);
        return $result;
    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "GetBoletosByOrden", $e);
    }
}


function GetAsientoByFuncion($ID_Funcion)
{
    try {
        $conn = OpenDB();
        $sql = "CALL sp_GetAsientoByFuncion('$ID_Funcion')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $ID_Funcion);
        $stmt->execute();

        $result = $stmt->get_result();

        CloseDB($conn);
        return $result;
    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "GetAsientoByFuncion", $e);
    }
}


function GetAsientoLibreByFuncion($ID_Funcion)
{
    try {
        $conn = OpenDB();
        $sql = "CALL sp_GetAsientoLibreByFuncion('$ID_Funcion')";
        $result = mysqli_query($conn, $sql);
        CloseDB($conn);
        return $result;
    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "GetAsientoLibreByFuncion", $e);
    }
}

function GetPrecioOfFuncion($ID_Funcion)
{
    try {
        $conn = OpenDB();
        $sql = "CALL sp_GetPrecioOfFuncion('$ID_Funcion')";
        $result = mysqli_query($conn, $sql);
        CloseDB($conn);
        return $result;
    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "GetPrecioOfFuncion", $e);
    }
}

function GetOrdenById($ID_Orden)
{
    try {
        $conn = OpenDB();
        $sql = "CALL sp_GetOrdenById('$ID_Orden')";
        $result = mysqli_query($conn, $sql);
        CloseDB($conn);
        return $result;
    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "GetOrdenById", $e);
    }
}

function GetFuncionById($ID_Funcion)
{
    try {
        $conn = OpenDB();
        $sql = "CALL sp_GetFuncionById('$ID_Funcion')";
        $result = mysqli_query($conn, $sql);
        CloseDB($conn);
        return $result;
    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "GetFuncionById", $e);
    }
}


function ActualizarEstadoOrden($ID_Orden, $Estado)
{
    try {
        $conn = OpenDB();
        $sql = "CALL sp_ActualizarEstadoOrden('$ID_Orden', '$Estado')";
        $response = $conn->query($sql);

        CloseDB($conn);
        return $response;
    } catch (Exception $e) {

        addLog(timestamp(), "ERROR", "ActualizarEstadoOrden", $e);
        return false;
    }
}
