<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/Model/OrdenModel.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/Model/PeliculaModel.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$ID_Cliente = $_SESSION["ID_Cliente"];

//no puedo estar aqui sin login
if(!$ID_Cliente){

    header("Location: ../View/IniciarSesion.php");
    exit();
}

function CargarFuncion()
{
    $ID_Funcion = filter_input(INPUT_GET, 'funcion', FILTER_VALIDATE_INT);
    $asientosLibres = GetAsientoLibreByFuncion($ID_Funcion);
    return [$ID_Funcion, $asientosLibres];
}




if (isset($_POST["btnReservarEntrada"])) {

    $ID_Funcion = filter_input(INPUT_GET, 'funcion', FILTER_VALIDATE_INT);
    //for now hardcode values
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


//----------------------------

function CargarCompletarReserva()
{
    $ID_Orden   = $_SESSION["ID_Orden"] ?? null;
    $ID_Funcion = $_SESSION["ID_Funcion"] ?? null;

    if (!$ID_Orden || !$ID_Funcion) {
        header("Location: ../View/index.php");
        exit();
    }

    $orden   = mysqli_fetch_assoc(GetOrdenById($ID_Orden));
    $funcion = mysqli_fetch_assoc(GetFuncionById($ID_Funcion));
    $pelicula = mysqli_fetch_assoc(getPelicula($funcion['ID_Pelicula']));



    //comparar cliente de la orden vs de la _SESSION
    if (!$orden || $orden['ID_Cliente'] != $_SESSION["ID_Cliente"]) {
        header("Location: ../View/Funcion.php");
        exit();
    }

    return [
        'tituloPelicula'   => $pelicula['Titulo'] ?? '',
        'horaFuncion'      => $funcion['HoraInicio'] ?? '',
        'posterUrl'        => $pelicula['URLPoster'] ?? '',
        'cantidadEntradas' => $orden['cantidadEntradas'],
        'precioEntrada'    => $orden['Subtotal'] / max($orden['cantidadEntradas'], 1),
        'subtotal'         => $orden['Subtotal'],
        'descuento'        => $orden['Descuento'],
        'total'            => $orden['Total'],
        'ID_Orden'         => $ID_Orden,
    ];
}

if (isset($_POST["btnCompletarReserva"])) {

    $ID_Orden = $_SESSION["ID_Orden"] ?? null;

    $nombreCompleto = trim($_POST["nombreCompleto"] ?? '');
    $numeroTarjeta  = preg_replace('/\s+/', '', $_POST["numeroTarjeta"] ?? '');

    if (!$ID_Orden || $nombreCompleto === '' || $numeroTarjeta === '') {
        $Mensaje = "Por favor complete todos los campos.";
    } else {
        $pagoOk = ActualizarEstadoOrden($ID_Orden, "PAGADA");

        if ($pagoOk) {
            //crear boletos de asientos            

            var_dump($_SESSION["asientos"]);

            $TipoBoleto = $funcion['Formato'] ?? '';
            $ID_Funcion = $_SESSION["ID_Funcion"];
            $asientos = $_SESSION["asientos"];
            $boletos = [];
            foreach ($asientos as $ID_Asiento) {

                $ID_Boleto = AddBoleto($ID_Orden, $ID_Funcion, $ID_Asiento, $TipoBoleto);
                $boletos[] = mysqli_fetch_assoc($ID_Boleto);
            }
            
            header("Location: ../View/ConfirmacionOrden.php?orden=" . urlencode($ID_Orden));
            exit();
        } else {
            $Mensaje = "No se ha podido procesar el pago correctamente";
        }
    }
}

function CargarBoletosCreados($ID_Orden)
{
    $result = GetBoletosByOrden($ID_Orden);

    $boletos = [];
    while ($fila = mysqli_fetch_assoc($result)) {
        $boletos[] = $fila;
    }
    return $boletos;
}








    