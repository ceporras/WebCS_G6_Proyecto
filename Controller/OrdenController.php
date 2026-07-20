<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/Model/OrdenModel.php';

$ID_Funcion = filter_input(INPUT_GET, 'funcion', FILTER_VALIDATE_INT);
$ID_Sala = 1;

$asientosLibres = GetAsientoLibreByFuncion($ID_Funcion);

if (isset($_POST["btnReservarEntrada"])) {

    //for now hardcode values
    $ID_Cliente = 8;
    $ID_Promocion = 1;
    $Descuento = 0;

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
    $ID_Orden = AddOrden($ID_Cliente, $ID_Promocion, $Estado, $Subtotal, $Descuento, $Total);


    if ($ID_Orden) {
        //create boletos

        //$datos_boleto = AddBoleto($ID_Orden, $ID_Funcion, "1", "Estandar");

        header("Location: ../View/CompletarReserva.php");
        exit();
    } else {
        $_POST["Mensaje"] = "No se ha podido reservar la funcion correctamente";
    }
}
