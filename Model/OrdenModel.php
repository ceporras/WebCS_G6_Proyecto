<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/Model/UtilModel.php';

function AddOrden($ID_Cliente, $ID_Promocion, $Estado, $Subtotal, $Descuento, $Total)
{
    try {
        $conn = OpenDB();
        $sql = "CALL spAddOrden('$ID_Cliente', '$ID_Promocion', '$Estado', '$Subtotal', '$Descuento', '$Total')";
        $result = $conn->query($sql);

        $row = $result->fetch_assoc();
        $ID_Orden = $row["ID_Orden"];

        CloseDB($conn);
        return $ID_Orden;

    } catch (Exception $e) {
        //no error handling
        echo "DB error: $e";
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
        
        echo "DB error: $e";
        return false;
    }
}

function listAsientos($ID_Sala)
{
    $conn = OpenDB();

    $sql = "SELECT ID_Asiento, Fila, Numero
            FROM asiento_tb WHERE ID_Sala = ?
            ORDER BY Fila, Numero";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ID_Sala);
    $stmt->execute();

    $result = $stmt->get_result();

    CloseDB($conn);
    return $result;
}

function listAsientosReservados($ID_Funcion)
{
    $conn = OpenDB();

    $sql = "SELECT ID_Asiento
            FROM boleto_tb
            WHERE ID_Funcion=$ID_Funcion";

    $result = mysqli_query($conn, $sql);

    $reserved = [];

    while($row = mysqli_fetch_assoc($result))
    {
        $reserved[] = $row['ID_Asiento'];
    }

    return $reserved;
}


function GetAsientoByFuncion($ID_Funcion)
{
    $conn = OpenDB();

    $sql = "CALL sp_GetAsientoByFuncion('$ID_Funcion')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ID_Funcion);
    $stmt->execute();

    $result = $stmt->get_result();

    CloseDB($conn);
    return $result;
}


function GetAsientoLibreByFuncion($ID_Funcion)
{
    $conn = OpenDB();
    $sql = "CALL sp_GetAsientoLibreByFuncion('$ID_Funcion')";
    $result = mysqli_query($conn, $sql);
    CloseDB($conn);
    return $result;
}