<?php

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/ExtLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/IntLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Model/PeliculaModel.php';



$generos = ConsultarGenerosModel();
$generoEditar = null;

if (isset($_GET['editarGenero'])) {

    $idGenero = filter_input(
        INPUT_GET,
        'editarGenero',
        FILTER_VALIDATE_INT
    );

    if ($idGenero) {
        $generoEditar = ConsultarGeneroPorIdModel($idGenero);
    }
}

function EscaparGenero($valor)
{
    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        'UTF-8'
    );
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

    <title>Administrar géneros | Golden Frame Cinemas</title>

    <?php ImportCSS(); ?>
</head>

<body>

<?php Navbar(); ?>

<main class="seccion">

    <div class="container">

        <div class="text-center mb-5">
            <h1 class="titulo-seccion">
                Administración de géneros
            </h1>

            <p class="texto-seccion">
                Registra y administra los géneros disponibles
                para las películas.
            </p>

            <a
                href="AdmPeliculas.php"
                class="btn btn-dorado"
            >
                Administrar películas
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
                <?= EscaparGenero($_SESSION['Mensaje']) ?>

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

        <div class="row justify-content-center">

            <div class="col-lg-5 mb-4">

                <div class="promo-card h-100">

                    <?php if ($generoEditar): ?>

                        <h2 class="mb-4">
                            Actualizar género
                        </h2>

                        <form
                            action="../Controller/PeliculaController.php"
                            method="POST"
                        >
                            <input
                                type="hidden"
                                name="idGenero"
                                value="<?= EscaparGenero(
                                    $generoEditar['ID_Genero']
                                ) ?>"
                            >

                            <div class="mb-3">
                                <label
                                    for="nombreGenero"
                                    class="form-label"
                                >
                                    Nombre del género
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="nombreGenero"
                                    name="nombreGenero"
                                    maxlength="45"
                                    required
                                    value="<?= EscaparGenero(
                                        $generoEditar['Nombre']
                                    ) ?>"
                                >
                            </div>

                            <div class="d-flex gap-2 flex-wrap">

                                <button
                                    type="submit"
                                    name="btnActualizarGenero"
                                    class="btn btn-dorado"
                                >
                                    Actualizar
                                </button>

                                <a
                                    href="AdmGeneros.php"
                                    class="btn btn-secondary"
                                >
                                    Cancelar
                                </a>

                            </div>
                        </form>

                    <?php else: ?>

                        <h2 class="mb-4">
                            Registrar género
                        </h2>

                        <form
                            action="../Controller/PeliculaController.php"
                            method="POST"
                        >
                            <div class="mb-3">

                                <label
                                    for="nombreGenero"
                                    class="form-label"
                                >
                                    Nombre del género
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="nombreGenero"
                                    name="nombreGenero"
                                    maxlength="45"
                                    placeholder="Ejemplo: Acción"
                                    required
                                >

                            </div>

                            <button
                                type="submit"
                                name="btnRegistrarGenero"
                                class="btn btn-dorado"
                            >
                                Registrar género
                            </button>
                        </form>

                    <?php endif; ?>

                </div>
            </div>

            <div class="col-lg-7 mb-4">

                <div class="promo-card h-100">

                    <h2 class="mb-4">
                        Géneros registrados
                    </h2>

                    <div class="table-responsive">

                        <table
                            class="table table-dark table-hover align-middle"
                        >
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th class="text-center">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>

                            <tbody>

                            <?php if (!empty($generos)): ?>

                                <?php foreach ($generos as $genero): ?>

                                    <tr>
                                        <td>
                                            <?= EscaparGenero(
                                                $genero['ID_Genero']
                                            ) ?>
                                        </td>

                                        <td>
                                            <?= EscaparGenero(
                                                $genero['Nombre']
                                            ) ?>
                                        </td>

                                        <td>
                                            <div
                                                class="d-flex justify-content-center gap-2 flex-wrap"
                                            >
                                                <a
                                                    href="AdmGeneros.php?editarGenero=<?= EscaparGenero(
                                                        $genero['ID_Genero']
                                                    ) ?>"
                                                    class="btn btn-warning btn-sm"
                                                >
                                                    Editar
                                                </a>

                                                <form
                                                    action="../Controller/PeliculaController.php"
                                                    method="POST"
                                                    onsubmit="return confirm('¿Desea eliminar este género?');"
                                                >
                                                    <input
                                                        type="hidden"
                                                        name="idGenero"
                                                        value="<?= EscaparGenero(
                                                            $genero['ID_Genero']
                                                        ) ?>"
                                                    >

                                                    <button
                                                        type="submit"
                                                        name="btnEliminarGenero"
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
                                        colspan="3"
                                        class="text-center"
                                    >
                                        No existen géneros registrados.
                                    </td>
                                </tr>

                            <?php endif; ?>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </div>
    </div>

</main>

<?php
Footer();
ImportJS();
?>

</body>
</html>