<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/View/IntLayout.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/View/ExtLayout.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/Controller/OrdenController.php';
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golden Frame Cinemas</title>

    <?php
    ImportCSS();
    ?>
</head>

<body>


    <?php
    Navbar();
    ?>

    <main class="container py-5" style="margin-top: 90px; min-height: 70vh;">
        <h2>Completar pago</h2>
        <form action="" method="post" class="needs-validation mt-3" id="formPago" name="formPago">
            <div class="row">
                
                    <div class="col-md-3 mb-3">
                        <h5 class="mb-3">Detalle de compra</h5>

                        <img src="https://image.tmdb.org/t/p/w500/t6HIqrRAclMCA60NsSmeqe9RmNV.jpg"
                            alt="Título de Película"
                            class="img-fluid rounded shadow w-75">

                        <div>
                            <label class="form-label">Titulo peli</label>
                        </div>
                        <div>
                            <label class="form-label">Hora de funcion</label>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div>
                            <label class="form-label">Cantidad de entradas</label>
                        </div>
                        <div>
                            <label class="form-label">Precio por entrada</label>
                        </div>
                        <div>
                            <label class="form-label">Subtotal</label>
                        </div>
                        <div>
                            <label class="form-label">Descuento</label>
                        </div>
                        <div>
                            <label class="form-label">Total</label>
                        </div>
                    



                </div>
                <div class="col-md-6 mb-6">
                    <h5 class="mb-3">Información de pago</h5>
                    <div class="mb-3">
                        <label for="nombreCompleto" class="form-label">Nombre completo</label>
                        <input type="text" class="form-control" id="nombreCompleto" name="nombreCompleto"
                            placeholder="Ej. Juan Pérez Rodríguez" >
                    </div>
                    <!-- Dirección -->
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion"
                            placeholder="Calle, número, ciudad, código postal" >
                    </div>

                    <!-- Correo electrónico -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="correoElectronico" name="correoElectronico"
                            placeholder="correo@ejemplo.com" >
                    </div>

                    <!-- Nombre en la tarjeta -->
                    <div class="mb-3">
                        <label for="nombreTarjeta" class="form-label">Nombre en la tarjeta</label>
                        <input type="text" class="form-control" id="nombreTarjeta" name="nombreTarjeta"
                            placeholder="Como aparece en la tarjeta" >
                    </div>

                    <!-- Número de tarjeta -->
                    <div class="mb-3">
                        <label for="numeroTarjeta" class="form-label">Número de tarjeta</label>
                        <input type="text" class="form-control" id="numeroTarjeta" name="numeroTarjeta"
                            inputmode="numeric" autocomplete="cc-number"
                            placeholder="0000 0000 0000 0000" maxlength="19" >
                    </div>

                    <div class="row">
                        <!-- Fecha de expiración -->
                        <div class="col-6 mb-3">
                            <label for="expiracion" class="form-label">Fecha de expiración</label>
                            <input type="text" class="form-control" id="expiracion" name="expiracion"
                                inputmode="numeric" autocomplete="cc-exp"
                                placeholder="MM/AA" maxlength="5" >
                        </div>

                        <!-- CVV -->
                        <div class="col-6 mb-4">
                            <label for="cvv" class="form-label">
                                CVV
                                <span class="text-muted" style="font-weight:normal;">(3 o 4 dígitos)</span>
                            </label>
                            <input type="text" class="form-control" id="cvv" name="cvv"
                                inputmode="numeric" autocomplete="cc-csc"
                                placeholder="123" maxlength="4" >
                        </div>
                    </div>



                </div>




                <button type="submit" class="btn btn-warning" id="btnCompletarReserva" name="btnCompletarReserva">
                    Completar Compra
                </button>
            </div>
        </form>
    </main>



    <!-- CONTACTO -->
    <?php
    Footer();
    ImportJS();
    ?>
    <script src="js/compra.js"></script>

</body>

</html>