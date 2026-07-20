<?php

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/ExtLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/IntLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Model/PeliculaModel.php';


$peliculas = ConsultarPeliculasModel();
$generos = ConsultarGenerosModel();
$peliculaEditar = null;
$generosSeleccionados = [];

if (isset($_GET['editarPelicula'])) {

    $idPelicula = filter_input(
        INPUT_GET,
        'editarPelicula',
        FILTER_VALIDATE_INT
    );

    if ($idPelicula) {

        $peliculaEditar =
            ConsultarPeliculaPorIdModel($idPelicula);

        if (
            $peliculaEditar &&
            isset($peliculaEditar['GenerosSeleccionados'])
        ) {
            foreach (
                $peliculaEditar['GenerosSeleccionados']
                as $generoSeleccionado
            ) {
                $generosSeleccionados[] =
                    (int) $generoSeleccionado['ID_Genero'];
            }
        }
    }
}

function EscaparPelicula($valor)
{
    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        'UTF-8'
    );
}

function SeleccionarOpcion($actual, $opcion)
{
    return $actual === $opcion ? 'selected' : '';
}
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Administrar películas | Golden Frame Cinemas</title>

    <?php ImportCSS(); ?>
</head>

<body>

<?php Navbar(); ?>

<main class="seccion">

    <div class="container">

        <div class="text-center mb-5">

            <h1 class="titulo-seccion">
                Administración de películas
            </h1>

            <p class="texto-seccion">
                Registra, consulta y administra la cartelera
                de Golden Frame Cinemas.
            </p>

            <a
                href="Generos.php"
                class="btn btn-dorado"
            >
                Administrar géneros
            </a>

        </div>

        <?php if (isset($_SESSION['Mensaje'])): ?>

            <?php
            $tipoMensaje =
                ($_SESSION['TipoMensaje'] ?? 'success') === 'danger'
                    ? 'danger'
                    : 'success';
            ?>

            <div
                class="alert alert-<?= $tipoMensaje ?> alert-dismissible fade show"
                role="alert"
            >
                <?= EscaparPelicula($_SESSION['Mensaje']) ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Cerrar"
                ></button>
            </div>

            <?php
            unset($_SESSION['Mensaje']);
            unset($_SESSION['TipoMensaje']);
            ?>

        <?php endif; ?>

        <section class="promo-card mb-5">

            <h2 class="mb-4">
                <?= $peliculaEditar
                    ? 'Actualizar película'
                    : 'Registrar película'
                ?>
            </h2>

            <form
                action="../Controller/PeliculaController.php"
                method="POST"
            >

                <?php if ($peliculaEditar): ?>

                    <input
                        type="hidden"
                        name="idPelicula"
                        value="<?= EscaparPelicula(
                            $peliculaEditar['ID_Pelicula']
                        ) ?>"
                    >

                <?php endif; ?>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label
                            for="titulo"
                            class="form-label"
                        >
                            Título
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="titulo"
                            name="titulo"
                            maxlength="45"
                            required
                            value="<?= EscaparPelicula(
                                $peliculaEditar['Titulo'] ?? ''
                            ) ?>"
                        >

                    </div>

                    <div class="col-md-3 mb-3">

                        <label
                            for="duracion"
                            class="form-label"
                        >
                            Duración
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                class="form-control"
                                id="duracion"
                                name="duracion"
                                min="1"
                                required
                                value="<?= EscaparPelicula(
                                    $peliculaEditar['Duracion'] ?? ''
                                ) ?>"
                            >

                            <span class="input-group-text">
                                min
                            </span>

                        </div>
                    </div>

                    <div class="col-md-3 mb-3">

                        <label
                            for="clasificacion"
                            class="form-label"
                        >
                            Clasificación
                        </label>

                        <?php
                        $clasificacionActual =
                            $peliculaEditar['Clasificacion'] ?? '';
                        ?>

                        <select
                            class="form-select"
                            id="clasificacion"
                            name="clasificacion"
                            required
                        >
                            <option value="">
                                Seleccione
                            </option>

                            <option
                                value="Todo público"
                                <?= SeleccionarOpcion(
                                    $clasificacionActual,
                                    'Todo público'
                                ) ?>
                            >
                                Todo público
                            </option>

                            <option
                                value="+12"
                                <?= SeleccionarOpcion(
                                    $clasificacionActual,
                                    '+12'
                                ) ?>
                            >
                                +12
                            </option>

                            <option
                                value="+15"
                                <?= SeleccionarOpcion(
                                    $clasificacionActual,
                                    '+15'
                                ) ?>
                            >
                                +15
                            </option>

                            <option
                                value="+18"
                                <?= SeleccionarOpcion(
                                    $clasificacionActual,
                                    '+18'
                                ) ?>
                            >
                                +18
                            </option>

                        </select>
                    </div>

                    <div class="col-md-6 mb-3">

                        <label
                            for="fechaEstreno"
                            class="form-label"
                        >
                            Fecha de estreno
                        </label>

                        <input
                            type="date"
                            class="form-control"
                            id="fechaEstreno"
                            name="fechaEstreno"
                            required
                            value="<?= EscaparPelicula(
                                $peliculaEditar['FechaEstreno'] ?? ''
                            ) ?>"
                        >

                    </div>

                    <div class="col-md-6 mb-3">

                        <label
                            for="idioma"
                            class="form-label"
                        >
                            Idioma
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="idioma"
                            name="idioma"
                            maxlength="45"
                            placeholder="Ejemplo: Español"
                            required
                            value="<?= EscaparPelicula(
                                $peliculaEditar['Idioma'] ?? ''
                            ) ?>"
                        >

                    </div>

                    <div class="col-12 mb-3">

                        <label
                            for="sinopsis"
                            class="form-label"
                        >
                            Sinopsis
                        </label>

                        <textarea
                            class="form-control"
                            id="sinopsis"
                            name="sinopsis"
                            rows="5"
                            maxlength="2000"
                            required
                        ><?= EscaparPelicula(
                            $peliculaEditar['Sinopsis'] ?? ''
                        ) ?></textarea>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label
                            for="urlTrailer"
                            class="form-label"
                        >
                            URL del tráiler
                        </label>

                        <input
                            type="url"
                            class="form-control"
                            id="urlTrailer"
                            name="urlTrailer"
                            maxlength="2000"
                            placeholder="https://..."
                            value="<?= EscaparPelicula(
                                $peliculaEditar['URLTrailer'] ?? ''
                            ) ?>"
                        >

                    </div>

                    <div class="col-md-6 mb-3">

                        <label
                            for="urlPoster"
                            class="form-label"
                        >
                            URL del póster
                        </label>

                        <input
                            type="url"
                            class="form-control"
                            id="urlPoster"
                            name="urlPoster"
                            maxlength="2000"
                            placeholder="https://..."
                            value="<?= EscaparPelicula(
                                $peliculaEditar['URLPoster'] ?? ''
                            ) ?>"
                        >

                    </div>

                    <?php if ($peliculaEditar): ?>

                        <div class="col-md-6 mb-3">

                            <label
                                for="estado"
                                class="form-label"
                            >
                                Estado
                            </label>

                            <select
                                class="form-select"
                                id="estado"
                                name="estado"
                            >
                                <option
                                    value="1"
                                    <?= (int) $peliculaEditar['Estado'] === 1
                                        ? 'selected'
                                        : ''
                                    ?>
                                >
                                    Activa
                                </option>

                                <option
                                    value="0"
                                    <?= (int) $peliculaEditar['Estado'] === 0
                                        ? 'selected'
                                        : ''
                                    ?>
                                >
                                    Inactiva
                                </option>
                            </select>

                        </div>

                    <?php endif; ?>

                    <div class="col-12 mb-4">

                        <label class="form-label">
                            Géneros
                        </label>

                        <?php if (!empty($generos)): ?>

                            <div class="row">

                                <?php foreach ($generos as $genero): ?>

                                    <?php
                                    $idGenero =
                                        (int) $genero['ID_Genero'];
                                    ?>

                                    <div class="col-md-3 col-sm-6 mb-2">

                                        <div class="form-check">

                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="generos[]"
                                                id="genero<?= $idGenero ?>"
                                                value="<?= $idGenero ?>"
                                                <?= in_array(
                                                    $idGenero,
                                                    $generosSeleccionados,
                                                    true
                                                )
                                                    ? 'checked'
                                                    : ''
                                                ?>
                                            >

                                            <label
                                                class="form-check-label"
                                                for="genero<?= $idGenero ?>"
                                            >
                                                <?= EscaparPelicula(
                                                    $genero['Nombre']
                                                ) ?>
                                            </label>

                                        </div>
                                    </div>

                                <?php endforeach; ?>

                            </div>

                        <?php else: ?>

                            <div class="alert alert-warning">
                                Debe registrar al menos un género antes
                                de registrar películas.
                            </div>

                        <?php endif; ?>

                    </div>

                </div>

                <div class="d-flex gap-2 flex-wrap">

                    <?php if ($peliculaEditar): ?>

                        <button
                            type="submit"
                            name="btnActualizarPelicula"
                            class="btn btn-dorado"
                        >
                            Actualizar película
                        </button>

                        <a
                            href="Peliculas.php"
                            class="btn btn-secondary"
                        >
                            Cancelar
                        </a>

                    <?php else: ?>

                        <button
                            type="submit"
                            name="btnRegistrarPelicula"
                            class="btn btn-dorado"
                            <?= empty($generos) ? 'disabled' : '' ?>
                        >
                            Registrar película
                        </button>

                    <?php endif; ?>

                </div>

            </form>

        </section>

        <section>

            <h2 class="titulo-seccion">
                Películas registradas
            </h2>

            <div class="table-responsive">

                <table
                    class="table table-dark table-hover align-middle"
                >
                    <thead>
                        <tr>
                            <th>Póster</th>
                            <th>Título</th>
                            <th>Duración</th>
                            <th>Clasificación</th>
                            <th>Idioma</th>
                            <th>Géneros</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (!empty($peliculas)): ?>

                        <?php foreach ($peliculas as $pelicula): ?>

                            <tr>

                                <td>

                                    <?php if (
                                        !empty($pelicula['URLPoster'])
                                    ): ?>

                                        <img
                                            src="<?= EscaparPelicula(
                                                $pelicula['URLPoster']
                                            ) ?>"
                                            alt="Póster de <?= EscaparPelicula(
                                                $pelicula['Titulo']
                                            ) ?>"
                                            style="
                                                width: 70px;
                                                height: 100px;
                                                object-fit: cover;
                                                border-radius: 6px;
                                            "
                                        >

                                    <?php else: ?>

                                        Sin imagen

                                    <?php endif; ?>

                                </td>

                                <td>
                                    <?= EscaparPelicula(
                                        $pelicula['Titulo']
                                    ) ?>
                                </td>

                                <td>
                                    <?= EscaparPelicula(
                                        $pelicula['Duracion']
                                    ) ?>
                                    min
                                </td>

                                <td>
                                    <?= EscaparPelicula(
                                        $pelicula['Clasificacion']
                                    ) ?>
                                </td>

                                <td>
                                    <?= EscaparPelicula(
                                        $pelicula['Idioma']
                                    ) ?>
                                </td>

                                <td>
                                    <?= EscaparPelicula(
                                        $pelicula['Generos']
                                        ?? 'Sin género'
                                    ) ?>
                                </td>

                                <td>

                                    <?php if (
                                        (int) $pelicula['Estado'] === 1
                                    ): ?>

                                        <span class="badge bg-success">
                                            Activa
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-danger">
                                            Inactiva
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <div class="d-flex gap-2 flex-wrap">

                                        <a
                                            href="Peliculas.php?editarPelicula=<?= EscaparPelicula(
                                                $pelicula['ID_Pelicula']
                                            ) ?>"
                                            class="btn btn-warning btn-sm"
                                        >
                                            Editar
                                        </a>

                                        <form
                                            action="../Controller/PeliculaController.php"
                                            method="POST"
                                        >
                                            <input
                                                type="hidden"
                                                name="idPelicula"
                                                value="<?= EscaparPelicula(
                                                    $pelicula['ID_Pelicula']
                                                ) ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="estado"
                                                value="<?= (int) $pelicula['Estado'] === 1
                                                    ? '0'
                                                    : '1'
                                                ?>"
                                            >

                                            <button
                                                type="submit"
                                                name="btnCambiarEstadoPelicula"
                                                class="btn btn-primary btn-sm"
                                            >
                                                <?= (int) $pelicula['Estado'] === 1
                                                    ? 'Desactivar'
                                                    : 'Activar'
                                                ?>
                                            </button>
                                        </form>

                                        <form
                                            action="../Controller/PeliculaController.php"
                                            method="POST"
                                            onsubmit="return confirm('¿Desea eliminar esta película permanentemente?');"
                                        >
                                            <input
                                                type="hidden"
                                                name="idPelicula"
                                                value="<?= EscaparPelicula(
                                                    $pelicula['ID_Pelicula']
                                                ) ?>"
                                            >

                                            <button
                                                type="submit"
                                                name="btnEliminarPelicula"
                                                class="btn btn-danger btn-sm"
                                            >
                                                Eliminar
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td
                                colspan="8"
                                class="text-center"
                            >
                                No existen películas registradas.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>
                </table>

            </div>
        </section>

    </div>

</main>

<?php
Footer();
ImportJS();
?>

</body>
</html>