<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/View/IntLayout.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/View/ExtLayout.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/Controller/OrdenController.php';
$datosPago = CargarCompletarReserva();
$ID_Orden = filter_input(INPUT_GET, 'orden', FILTER_VALIDATE_INT);
$boletos = CargarBoletosCreados($ID_Orden);
//borrar variables de la compra, ya fue completada
unset($_SESSION["ID_Orden"], $_SESSION["ID_Funcion"], $_SESSION["asientos"]);
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
        <h2 class="mb-3">Su orden ha sido completada exitosamente</h2>
        <div class="row">

            <div class="col-md-4 mb-3">
                <h5 class="mb-3">Detalle de pelicula</h5>

                <img src="<?= htmlspecialchars($datosPago['posterUrl']) ?>"
                    alt="<?= htmlspecialchars($datosPago['tituloPelicula']) ?>"
                    class="img-fluid rounded shadow w-75">

                <div>
                    <label class="form-label">Titulo de pelicula: <?= htmlspecialchars($datosPago['tituloPelicula']) ?></label>
                </div>
                <div>
                    <label class="form-label">Hora de la funcion: <?= htmlspecialchars($datosPago['horaFuncion']) ?></label>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <h5 class="mb-3">Detalle de compra</h5>
                <div>
                    <label class="form-label">Cantidad de entradas: <?= (int) $datosPago['cantidadEntradas'] ?></label>
                </div>
                <div>
                    <label class="form-label">Precio por entrada: ₡<?= number_format($datosPago['precioEntrada'], 2) ?></label>
                </div>
                <div>
                    <label class="form-label">Subtotal: ₡<?= number_format($datosPago['subtotal'], 2) ?></label>
                </div>
                <div>
                    <label class="form-label">Descuento: <?= (float) $datosPago['descuento'] ?> %</label>
                </div>
                <div>
                    <label class="form-label">Total: ₡<?= number_format($datosPago['total'], 2) ?></label>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <h5 class="mb-3">Detalle del Cine</h5>
                <?php if (!empty($boletos)): ?>
                    <div class="mb-2">
                        <strong><?= htmlspecialchars($boletos[0]['NombreCine']) ?></strong><br>
                        <span ><?= htmlspecialchars($boletos[0]['DireccionCine']) ?></span><br>
                        <span ><?= htmlspecialchars($boletos[0]['NombreSala']) ?></span>
                    </div>
                    <h5 class="mb-3">Detalle de boletos</h5>
                    <ul class="list-unstyled">
                        <?php foreach ($boletos as $boleto): ?>
                            <li class="mb-1">
                                🎟️ Boleto #<?= (int) $boleto['Boleto'] ?> — Asiento <?= htmlspecialchars($boleto['Asiento']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">No se encontraron boletos.</p>
                <?php endif; ?>
            </div>
    </main>



    <!-- CONTACTO -->
    <?php
    Footer();
    ImportJS();
    ?>


</body>

</html>