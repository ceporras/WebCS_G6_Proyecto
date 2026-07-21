<?php

session_start();

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/IntLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/ExtLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Controller/CineController.php';

$cines = ConsultarCinesModel();
$cineEditar = null;

if (isset($_GET['editarCine'])) {
    $idCine = filter_input(
        INPUT_GET,
        'editarCine',
        FILTER_VALIDATE_INT
    );

    if ($idCine) {
        $cineEditar = ConsultarCinePorIdModel($idCine);
    }
}

function EscaparCine($valor)
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

    <title>Administración de cines</title>

    <?php ImportCSS(); ?>
</head>

<body>

<?php Navbar(); ?>

<main
    class="container py-5"
    style="margin-top: 90px; min-height: 70vh;"
>

    <div
        class="d-flex flex-column flex-md-row
               justify-content-between align-items-md-center
               gap-3 mb-4"
    >
        <div>
            <h1 class="mb-1">
                Administración de cines
            </h1>

            <p class="text-secondary mb-0">
                Registra, modifica y elimina los cines disponibles.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a
                href="/WebCS_G6_Proyecto/View/Sala.php"
                class="btn btn-outline-warning"
            >
                Administrar salas
            </a>

            <a
                href="/WebCS_G6_Proyecto/View/Asiento.php"
                class="btn btn-outline-warning"
            >
                Administrar asientos
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['Mensaje'])): ?>

        <?php
        $tipoMensaje =
            ($_SESSION['TipoMensaje'] ?? 'success') === 'danger'
                ? 'danger'
                : 'success';
        ?>

        <div
            class="alert alert-<?= EscaparCine($tipoMensaje) ?>
                   alert-dismissible fade show"
            role="alert"
        >
            <?= EscaparCine($_SESSION['Mensaje']) ?>

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

    <div class="row">

        <div class="col-lg-5 mb-4">

            <div class="card shadow h-100">

                <div class="card-header">
                    <strong>
                        <?= $cineEditar
                            ? 'Actualizar cine'
                            : 'Registrar cine'
                        ?>
                    </strong>
                </div>

                <div class="card-body">

                    <form
                        action="/WebCS_G6_Proyecto/Controller/CineController.php"
                        method="POST"
                    >

                        <?php if ($cineEditar): ?>

                            <input
                                type="hidden"
                                name="idCine"
                                value="<?= EscaparCine(
                                    $cineEditar['ID_Cine']
                                ) ?>"
                            >

                        <?php endif; ?>

                        <div class="mb-3">

                            <label
                                for="nombre"
                                class="form-label"
                            >
                                Nombre
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nombre"
                                name="nombre"
                                maxlength="45"
                                required
                                placeholder="Ejemplo: Golden Frame Escazú"
                                value="<?= EscaparCine(
                                    $cineEditar['Nombre'] ?? ''
                                ) ?>"
                            >

                        </div>

                        <div class="mb-3">

                            <label
                                for="direccion"
                                class="form-label"
                            >
                                Dirección
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="direccion"
                                name="direccion"
                                maxlength="200"
                                required
                                placeholder="Dirección del cine"
                                value="<?= EscaparCine(
                                    $cineEditar['Direccion'] ?? ''
                                ) ?>"
                            >

                        </div>

                        <div class="mb-3">

                            <label
                                for="ciudad"
                                class="form-label"
                            >
                                Ciudad
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="ciudad"
                                name="ciudad"
                                maxlength="45"
                                required
                                placeholder="Ejemplo: San José"
                                value="<?= EscaparCine(
                                    $cineEditar['Ciudad'] ?? ''
                                ) ?>"
                            >

                        </div>

                        <div class="mb-3">

                            <label
                                for="telefono"
                                class="form-label"
                            >
                                Teléfono
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="telefono"
                                name="telefono"
                                maxlength="45"
                                required
                                placeholder="Ejemplo: 2222-2222"
                                value="<?= EscaparCine(
                                    $cineEditar['Telefono'] ?? ''
                                ) ?>"
                            >

                        </div>

                        <div class="mb-3">

                            <label
                                for="correo"
                                class="form-label"
                            >
                                Correo
                            </label>

                            <input
                                type="email"
                                class="form-control"
                                id="correo"
                                name="correo"
                                maxlength="45"
                                required
                                placeholder="cine@goldenframe.com"
                                value="<?= EscaparCine(
                                    $cineEditar['Correo'] ?? ''
                                ) ?>"
                            >

                        </div>

                        <div class="d-flex gap-2 flex-wrap">

                            <button
                                type="submit"
                                name="<?= $cineEditar
                                    ? 'btnActualizarCine'
                                    : 'btnRegistrarCine'
                                ?>"
                                class="btn btn-warning"
                            >
                                <?= $cineEditar
                                    ? 'Guardar cambios'
                                    : 'Registrar cine'
                                ?>
                            </button>

                            <?php if ($cineEditar): ?>

                                <a
                                    href="/WebCS_G6_Proyecto/View/Cine.php"
                                    class="btn btn-secondary"
                                >
                                    Cancelar
                                </a>

                            <?php endif; ?>

                        </div>

                    </form>

                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">

            <div class="card shadow h-100">

                <div class="card-header">
                    <strong>Cines registrados</strong>
                </div>

                <div class="card-body table-responsive">

                    <table
                        class="table table-dark table-striped
                               table-hover align-middle mb-0"
                    >
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Ciudad</th>
                                <th>Teléfono</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php if (!empty($cines)): ?>

                            <?php foreach ($cines as $cine): ?>

                                <tr>
                                    <td>
                                        <?= EscaparCine(
                                            $cine['ID_Cine']
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= EscaparCine(
                                            $cine['Nombre']
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= EscaparCine(
                                            $cine['Ciudad']
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= EscaparCine(
                                            $cine['Telefono']
                                        ) ?>
                                    </td>

                                    <td class="text-nowrap">

                                        <a
                                            href="/WebCS_G6_Proyecto/View/Cine.php?editarCine=<?= EscaparCine(
                                                $cine['ID_Cine']
                                            ) ?>"
                                            class="btn btn-warning btn-sm"
                                        >
                                            Editar
                                        </a>

                                        <form
                                            action="/WebCS_G6_Proyecto/Controller/CineController.php"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm(
                                                '¿Desea eliminar este cine?'
                                            );"
                                        >
                                            <input
                                                type="hidden"
                                                name="idCine"
                                                value="<?= EscaparCine(
                                                    $cine['ID_Cine']
                                                ) ?>"
                                            >

                                            <button
                                                type="submit"
                                                name="btnEliminarCine"
                                                class="btn btn-danger btn-sm"
                                            >
                                                Eliminar
                                            </button>
                                        </form>

                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td
                                    colspan="5"
                                    class="text-center py-4"
                                >
                                    No existen cines registrados.
                                </td>
                            </tr>

                        <?php endif; ?>

                        </tbody>
                    </table>

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