<?php

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/ExtLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/IntLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Controller/PeliculaController.php';


if (isset($_GET['id_pelicula'])) {

    $ID_Pelicula = (int) $_GET['id_pelicula'];
    $pelicula = getPelicula($ID_Pelicula)->fetch_assoc();
    $funciones = getFuncionesByPelicula($ID_Pelicula);
} else {
    //si el URL no tiene ID de pelicula, no puedo estar aqui
    header("Location: ../View/index.php");
    exit();
}

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

                    <?php
                    //cargar los dias en los que hay funciones para el UL
                    $funcionesPorDia = [];
                    foreach ($funciones as $funcion) {
                        $fecha = date('Y-m-d', strtotime($funcion['HoraInicio']));
                        $funcionesPorDia[$fecha][] = $funcion;
                    }
                    ksort($funcionesPorDia);
                    if (is_object($funciones) && $funciones->num_rows == 0):?>
                        <h5 class="mb-4">Lo sentimos, no hay horarios disponibles para esta pelicula en este momento</h5>
                    <?php endif;    
                    ?>

                    <ul class="nav nav-tabs mb-3" id="funcionesTabs" role="tablist">
                        <?php
                        $first = true;
                        foreach ($funcionesPorDia as $fecha => $lista):
                        ?>
                            <li class="nav-item " role="presentation">
                                <button
                                    class="nav-link <?= $first ? 'active' : '' ?>"
                                    id="tab-<?= $fecha ?>"
                                    data-bs-toggle="tab"
                                    data-bs-target="#content-<?= $fecha ?>"
                                    type="button"
                                    role="tab">
                                    <?= date('D j M', strtotime($fecha)) ?>
                                </button>
                            </li>
                        <?php
                            $first = false;
                        endforeach;
                        ?>
                    </ul>

                    <div class="tab-content">

                        <?php
                        $first = true;
                        foreach ($funcionesPorDia as $fecha => $lista):
                        ?>

                            <div
                                class="tab-pane fade <?= $first ? 'show active' : '' ?>"
                                id="content-<?= $fecha ?>"
                                role="tabpanel">

                                <h6>Subtitulada</h6>
                                <div class="d-flex flex-wrap gap-2 mb-4">

                                    <?php foreach ($lista as $funcion): ?>

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
                                    <?php foreach ($lista as $funcion): ?>
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

                        <?php
                            $first = false;
                        endforeach;
                        ?>

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