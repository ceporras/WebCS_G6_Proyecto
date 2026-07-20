<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/Model/OrdenModel.php';

    $ID_Funcion = filter_input(INPUT_GET, 'funcion', FILTER_VALIDATE_INT);
    $asientosReservados = listAsientosReservados($ID_Funcion);
    $ID_Sala = 1;
    $asientosTodos = listAsientos($ID_Sala);
    
    $asientosLibres = GetAsientoLibreByFuncion($ID_Funcion);


    if (isset($_POST["btnReservarEntrada"])) {
        

        //for now hardcode values
        $ID_Cliente=8;
        $ID_Promocion=1;
        #$ID_Funcion=2;
        $Precio = 2500;
        $Descuento = 0;
        
        //valores de usuario
        $cantidadEntradas = $_POST["cantidadEntradas"];


        //add here a way to pull precio from funcion based on its id to use for subtotal

        $Subtotal = $Precio * $cantidadEntradas;

        //calcular descuentos y total
        if($Descuento>=0){
                $Total = $Subtotal * (1 - $Descuento / 100); }
        else{
                //no hay descuento
                $Total = $Subtotal;
            }
       
        //orden pendiente, sin pago
        $Estado = "PENDIENTE";
        /*$ID_Orden = AddOrden($ID_Cliente, $ID_Promocion, $Estado, $Subtotal, $Descuento, $Total);
        

        if($ID_Orden)
        {
            //create boletos

            $datos_boleto = AddBoleto($ID_Orden, $ID_Funcion, "1", "Estandar");


            
        }else
        {
            $_POST["Mensaje"] = "No se ha podido reservar la funcion correctamente";
        }*/

    }
