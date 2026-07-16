<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/View/IntLayout.php';
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

        <hr>

        <h2>Reservar Entradas</h2>

        <form action="" method="post" id="formReservar" name="formReservar">

            <div class="mb-3 w-25">
                <label class="form-label">Cantidad de Entradas</label>
                <select class="form-select" id="catidadEntradas" name="catidadEntradas">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                    <option>6</option>
                </select>
            </div>

            <button type="submit" class="btn btn-warning" id="btnReservarEntrada" name="btnReservarEntrada">
                Continuar Reserva
            </button>

        </form>
    </main>



    <!-- CONTACTO -->
    <?php
    Footer();
    ImportJS();
    ?>


</body>

</html>