<?php

include_once $_SERVER['DOCUMENT_ROOT']
. '/WebCS_G6_Proyecto/View/ExtLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
. '/WebCS_G6_Proyecto/View/IntLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
. '/WebCS_G6_Proyecto/Controller/FuncionController.php';

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

        <h2>Reservar Entradas</h2>
        <form action="" method="post" id="formReservar" name="formReservar">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label  mb-3">Cantidad de Entradas</label>
                    <select
                        class="form-select w-75" id="cantidadEntradas" name="cantidadEntradas">
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                    <hr>
                    <label class="form-label">Asientos Seleccionados</label>
                    <div id="listaAsientos">Ninguno</div>
                </div>
                <div class="col-md-3">
                    <div class="screen ">
                        <label class="form-label  mb-3">Selección de Asientos</label>
                    </div>
                    <div class="seat-map">

                        <select class="form-select w-75 mb-3" id="asientosDisponiblesSelect" name="asientosDisponiblesSelect">
                            <option value="">--- Asiento ---</option>

                            <?php while ($fila = mysqli_fetch_assoc($asientosLibres)) { ?>

                                <option value="<?= $fila['ID_Asiento'] ?>">
                                    <?= $fila['Asiento'] ?>
                                </option>

                            <?php } ?>

                        </select>
                        <button type="button" class="btn btn-warning " id="btnAgregarAsiento">
                            Agregar
                        </button>
                        <div id="hiddenSeats"></div>

                    </div>

                </div>
                <div class="col-md-6  mb-3">
                    <label class="form-label">Asientos en la sala</label>
                    <img src="/WebCS_G6_Proyecto/View/images/asientos-cine.png"
                        alt="Título de Película"
                        class="img-fluid rounded shadow">
                </div>


                <button type="submit" class="btn btn-warning" id="btnReservarEntrada" name="btnReservarEntrada">
                    Continuar Reserva
                </button>
            </div>
        </form>
    </main>



    <!-- CONTACTO -->
    <?php
    Footer();
    ImportJS();
    ?>
    <script src="js/asientos.js"></script>

</body>

</html>