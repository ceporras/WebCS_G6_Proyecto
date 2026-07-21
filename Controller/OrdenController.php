<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/Model/OrdenModel.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$ID_Funcion = filter_input(INPUT_GET, 'funcion', FILTER_VALIDATE_INT);

//cargar asientos disponibles
$asientosLibres = GetAsientoLibreByFuncion($ID_Funcion);

if (isset($_POST["btnReservarEntrada"])) {

    //for now hardcode values
    $ID_Promocion = 1;
    $Descuento = 0;

    $ID_Cliente = $_SESSION["ID_Cliente"];
    $cantidadEntradas = $_POST["cantidadEntradas"];
    $Precio = (float)  mysqli_fetch_assoc(GetPrecioOfFuncion($ID_Funcion))['Precio'] ?? '0.00';

    $Subtotal = $Precio * $cantidadEntradas;

    //aplicar descuentos a total
    if ($Descuento >= 0) {
        $Total = $Subtotal * (1 - $Descuento / 100);
    } else {
        //no hay descuento
        $Total = $Subtotal;
    }

    //orden pendiente, sin pago
    $Estado = "PENDIENTE";
    $ID_Orden = AddOrden($ID_Cliente, $ID_Promocion, $Estado, $cantidadEntradas, $Subtotal, $Descuento, $Total);

    if ($ID_Orden) {
        //agregar a sesion variables necesarias para completar orden
        $_SESSION["ID_Orden"] = $ID_Orden;
        $_SESSION["ID_Funcion"] = $ID_Funcion;
        $_SESSION["asientos"] = $_POST["asientos"];

        header("Location: ../View/CompletarReserva.php?orden=" . urlencode($ID_Orden));
        exit();
    } else {
        $_POST["Mensaje"] = "No se ha podido reservar la funcion correctamente";
    }

    
}


if (isset($_POST["btnReservarEntrada"])) {