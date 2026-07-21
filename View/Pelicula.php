<?php

include_once $_SERVER['DOCUMENT_ROOT']
. '/WebCS_G6_Proyecto/View/ExtLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
. '/WebCS_G6_Proyecto/View/IntLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
. '/WebCS_G6_Proyecto/Controller/PeliculaController.php';

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
        <div>
            <div class="row">
                <!-- Movie Information -->
                <div class="col-md-4 mb-4">
                    <img src="<?= htmlspecialchars($pelicula['URLPoster']) ?>"
                        alt="<?= htmlspecialchars($pelicula['Titulo']) ?>"
                        class="img-fluid rounded shadow">
                </div>

                <div class="col-md-8">
                    <h1><?= htmlspecialchars($pelicula['Titulo']) ?></h1>

                    <p class="lead">
                        <?= htmlspecialchars($pelicula['Sinopsis']) ?>
                    </p>

                    <div class="mb-4">
                        <span class="badge bg-warning text-dark">Acción</span>
                        <span class="badge bg-secondary"><?= FormatDuracion($pelicula['Duracion']) ?></span>
                        <span class="badge bg-danger">+13</span>
                    </div>

                    <hr>

                    <h2>Horarios Disponibles</h2>

                    <h6>Subtitulada</h6>
                    <div class="d-flex flex-wrap gap-2 mb-4">

                        <?php foreach ($funciones as $funcion): ?>

                            <?php if ($funcion['Idioma'] == 'SUB'): ?>

                                <a
                                    href="Funcion.php?funcion=<?= $funcion['ID_Funcion'] ?>"
                                    class="btn btn-outline-primary">

                                    <?= date('g:i A', strtotime($funcion['HoraInicio'])) ?>

                                </a>

                            <?php endif; ?>

                        <?php endforeach; ?>

                    </div>
                    <h6>Doblada</h6>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <?php foreach ($funciones as $funcion): ?>

                            <?php if ($funcion['Idioma'] == 'DOB'): ?>

                                <a
                                    href="Funcion.php?funcion=<?= $funcion['ID_Funcion'] ?>"
                                    class="btn btn-outline-primary">

                                    <?= date('g:i A', strtotime($funcion['HoraInicio'])) ?>

                                </a>

                            <?php endif; ?>

                        <?php endforeach; ?>
                    </div>

                    

                </div>
            </div>
    </main>



    <!-- CONTACTO -->
    <?php
    Footer();
    ImportJS();
    ?>


</body>

</html>