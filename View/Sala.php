<?php

session_start();

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/IntLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/ExtLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Controller/SalaController.php';

ProcesarSalaController();

$salas = ObtenerSalasController();
$cines = ObtenerCinesParaSalaController();

$salaEditar = null;

if (
    isset($_GET['editar']) &&
    filter_var($_GET['editar'], FILTER_VALIDATE_INT)
) {
    $salaEditar = ObtenerSalaPorIdController(
        (int) $_GET['editar']
    );
}

$mensaje = $_SESSION['mensajeSala'] ?? '';
$tipoMensaje = $_SESSION['tipoMensajeSala'] ?? 'info';

unset(
    $_SESSION['mensajeSala'],
    $_SESSION['tipoMensajeSala']
);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Administración de salas</title>

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
            <h1 class="mb-1">Administración de salas</h1>

            <p class="text-secondary mb-0">
                Registra, modifica y elimina las salas de cada cine.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a
                href="/WebCS_G6_Proyecto/View/Cine.php"
                class="btn btn-outline-warning"
            >
                Administrar cines
            </a>

            <a
                href="/WebCS_G6_Proyecto/View/Asiento.php"
                class="btn btn-outline-warning"
            >
                Administrar asientos
            </a>
        </div>
    </div>

    <?php if ($mensaje !== ''): ?>

        <div
            class="alert alert-<?= htmlspecialchars($tipoMensaje) ?>
                   alert-dismissible fade show"
            role="alert"
        >
            <?= htmlspecialchars($mensaje) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Cerrar"
            ></button>
        </div>

    <?php endif; ?>

    <div class="card shadow mb-4">

        <div class="card-header">
            <strong>
                <?= $salaEditar
                    ? 'Editar sala'
                    : 'Registrar sala'
                ?>
            </strong>
        </div>

        <div class="card-body">

            <?php if (empty($cines)): ?>

                <div class="alert alert-warning mb-0">
                    Primero debes registrar al menos un cine.
                </div>

            <?php else: ?>

                <form method="POST">

                    <input
                        type="hidden"
                        name="accion"
                        value="<?= $salaEditar
                            ? 'actualizar'
                            : 'registrar'
                        ?>"
                    >

                    <?php if ($salaEditar): ?>

                        <input
                            type="hidden"
                            name="ID_Sala"
                            value="<?= (int) $salaEditar['ID_Sala'] ?>"
                        >

                    <?php endif; ?>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label
                                for="ID_Cine"
                                class="form-label"
                            >
                                Cine
                            </label>

                            <select
                                name="ID_Cine"
                                id="ID_Cine"
                                class="form-select"
                                required
                            >
                                <option value="">
                                    Seleccione un cine
                                </option>

                                <?php foreach ($cines as $cine): ?>

                                    <?php
                                    $cineSeleccionado =
                                        $salaEditar &&
                                        (int) $salaEditar['ID_Cine'] ===
                                        (int) $cine['ID_Cine'];
                                    ?>

                                    <option
                                        value="<?= (int) $cine['ID_Cine'] ?>"
                                        <?= $cineSeleccionado
                                            ? 'selected'
                                            : ''
                                        ?>
                                    >
                                        <?= htmlspecialchars(
                                            $cine['Nombre']
                                        ) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>
                        </div>

                        <div class="col-md-6 mb-3">

                            <label
                                for="Nombre"
                                class="form-label"
                            >
                                Nombre de la sala
                            </label>

                            <input
                                type="text"
                                name="Nombre"
                                id="Nombre"
                                class="form-control"
                                maxlength="45"
                                required
                                placeholder="Ejemplo: Sala 1"
                                value="<?= htmlspecialchars(
                                    $salaEditar['Nombre'] ?? ''
                                ) ?>"
                            >

                        </div>

                        <div class="col-md-6 mb-3">

                            <label
                                for="Capacidad"
                                class="form-label"
                            >
                                Capacidad
                            </label>

                            <input
                                type="number"
                                name="Capacidad"
                                id="Capacidad"
                                class="form-control"
                                min="1"
                                required
                                placeholder="Ejemplo: 100"
                                value="<?= htmlspecialchars(
                                    $salaEditar['Capacidad'] ?? ''
                                ) ?>"
                            >

                        </div>

                        <div class="col-md-6 mb-3">

                            <label
                                for="TipoPantalla"
                                class="form-label"
                            >
                                Tipo de pantalla
                            </label>

                            <?php
                            $tipoActual =
                                $salaEditar['TipoPantalla'] ?? '';
                            ?>

                            <select
                                name="TipoPantalla"
                                id="TipoPantalla"
                                class="form-select"
                                required
                            >
                                <option value="">
                                    Seleccione una opción
                                </option>

                                <option
                                    value="2D"
                                    <?= $tipoActual === '2D'
                                        ? 'selected'
                                        : ''
                                    ?>
                                >
                                    2D
                                </option>

                                <option
                                    value="3D"
                                    <?= $tipoActual === '3D'
                                        ? 'selected'
                                        : ''
                                    ?>
                                >
                                    3D
                                </option>

                                <option
                                    value="IMAX"
                                    <?= $tipoActual === 'IMAX'
                                        ? 'selected'
                                        : ''
                                    ?>
                                >
                                    IMAX
                                </option>

                                <option
                                    value="4DX"
                                    <?= $tipoActual === '4DX'
                                        ? 'selected'
                                        : ''
                                    ?>
                                >
                                    4DX
                                </option>

                                <option
                                    value="VIP"
                                    <?= $tipoActual === 'VIP'
                                        ? 'selected'
                                        : ''
                                    ?>
                                >
                                    VIP
                                </option>
                            </select>

                        </div>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-warning"
                    >
                        <?= $salaEditar
                            ? 'Guardar cambios'
                            : 'Registrar sala'
                        ?>
                    </button>

                    <?php if ($salaEditar): ?>

                        <a
                            href="/WebCS_G6_Proyecto/View/Sala.php"
                            class="btn btn-secondary"
                        >
                            Cancelar
                        </a>

                    <?php endif; ?>

                </form>

            <?php endif; ?>

        </div>
    </div>

    <div class="card shadow">

        <div class="card-header">
            <strong>Salas registradas</strong>
        </div>

        <div class="card-body table-responsive">

            <table
                class="table table-dark table-striped
                       table-hover align-middle mb-0"
            >

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cine</th>
                        <th>Sala</th>
                        <th>Capacidad</th>
                        <th>Pantalla</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                <?php if (empty($salas)): ?>

                    <tr>
                        <td
                            colspan="6"
                            class="text-center py-4"
                        >
                            No hay salas registradas.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($salas as $sala): ?>

                        <tr>
                            <td>
                                <?= (int) $sala['ID_Sala'] ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $sala['NombreCine']
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $sala['Nombre']
                                ) ?>
                            </td>

                            <td>
                                <?= (int) $sala['Capacidad'] ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $sala['TipoPantalla']
                                ) ?>
                            </td>

                            <td class="text-nowrap">

                                <a
                                    href="/WebCS_G6_Proyecto/View/Sala.php?editar=<?= (int) $sala['ID_Sala'] ?>"
                                    class="btn btn-warning btn-sm"
                                >
                                    Editar
                                </a>

                                <form
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm(
                                        '¿Deseas eliminar esta sala?'
                                    );"
                                >
                                    <input
                                        type="hidden"
                                        name="accion"
                                        value="eliminar"
                                    >

                                    <input
                                        type="hidden"
                                        name="ID_Sala"
                                        value="<?= (int) $sala['ID_Sala'] ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                    >
                                        Eliminar
                                    </button>

                                </form>

                            </td>
                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

                </tbody>
            </table>

        </div>
    </div>

</main>

<?php Footer(); ?>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>
</html>