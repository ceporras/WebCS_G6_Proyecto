<?php

session_start();

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/IntLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/ExtLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Controller/AsientoController.php';

ProcesarAsientoController();

$asientos = ObtenerAsientosController();
$salas = ObtenerSalasParaAsientoController();

$asientoEditar = null;

if (
    isset($_GET['editar']) &&
    filter_var($_GET['editar'], FILTER_VALIDATE_INT)
) {
    $asientoEditar = ObtenerAsientoPorIdController(
        (int) $_GET['editar']
    );
}

$mensaje = $_SESSION['mensajeAsiento'] ?? '';
$tipoMensaje = $_SESSION['tipoMensajeAsiento'] ?? 'info';

unset(
    $_SESSION['mensajeAsiento'],
    $_SESSION['tipoMensajeAsiento']
);

function EscaparAsiento($valor)
{
    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        'UTF-8'
    );
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Administración de asientos</title>

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
                Administración de asientos
            </h1>

            <p class="text-secondary mb-0">
                Registra, modifica y elimina los asientos de cada sala.
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a
                href="/WebCS_G6_Proyecto/View/Sala.php"
                class="btn btn-outline-warning"
            >
                Administrar salas
            </a>

            <a
                href="/WebCS_G6_Proyecto/View/Cine.php"
                class="btn btn-outline-warning"
            >
                Administrar cines
            </a>
        </div>
    </div>

    <?php if ($mensaje !== ''): ?>

        <div
            class="alert alert-<?= EscaparAsiento($tipoMensaje) ?>
                   alert-dismissible fade show"
            role="alert"
        >
            <?= EscaparAsiento($mensaje) ?>

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
                <?= $asientoEditar
                    ? 'Editar asiento'
                    : 'Registrar asiento'
                ?>
            </strong>
        </div>

        <div class="card-body">

            <?php if (empty($salas)): ?>

                <div class="alert alert-warning mb-0">
                    Primero debes registrar al menos una sala.
                </div>

            <?php else: ?>

                <form method="POST">

                    <input
                        type="hidden"
                        name="accion"
                        value="<?= $asientoEditar
                            ? 'actualizar'
                            : 'registrar'
                        ?>"
                    >

                    <?php if ($asientoEditar): ?>

                        <input
                            type="hidden"
                            name="ID_Asiento"
                            value="<?= (int) $asientoEditar['ID_Asiento'] ?>"
                        >

                    <?php endif; ?>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label
                                for="ID_Sala"
                                class="form-label"
                            >
                                Sala
                            </label>

                            <select
                                name="ID_Sala"
                                id="ID_Sala"
                                class="form-select"
                                required
                            >
                                <option value="">
                                    Seleccione una sala
                                </option>

                                <?php foreach ($salas as $sala): ?>

                                    <?php
                                    $salaSeleccionada =
                                        $asientoEditar &&
                                        (int) $asientoEditar['ID_Sala'] ===
                                        (int) $sala['ID_Sala'];
                                    ?>

                                    <option
                                    value="<?= (int) $sala['ID_Sala'] ?>"
                                    <?= $salaSeleccionada
                                        ? 'selected'
                                        : ''
                                    ?>
                                >
                                    <?= EscaparAsiento(
                                        ($sala['NombreCine'] ?? '')
                                        . ' - '
                                        . ($sala['Nombre'] ?? '')
                                    ) ?>
                                </option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                        <div class="col-md-2 mb-3">

                            <label
                                for="Fila"
                                class="form-label"
                            >
                                Fila
                            </label>

                            <input
                                type="text"
                                name="Fila"
                                id="Fila"
                                class="form-control"
                                maxlength="10"
                                required
                                placeholder="Ejemplo: A"
                                value="<?= EscaparAsiento(
                                    $asientoEditar['Fila'] ?? ''
                                ) ?>"
                            >

                        </div>

                        <div class="col-md-2 mb-3">

                            <label
                                for="Numero"
                                class="form-label"
                            >
                                Número
                            </label>

                            <input
                                type="number"
                                name="Numero"
                                id="Numero"
                                class="form-control"
                                min="1"
                                required
                                placeholder="1"
                                value="<?= EscaparAsiento(
                                    $asientoEditar['Numero'] ?? ''
                                ) ?>"
                            >

                        </div>

                        <div class="col-md-2 mb-3">

                            <label
                                for="TipoAsiento"
                                class="form-label"
                            >
                                Tipo
                            </label>

                            <?php
                            $tipoActual =
                                $asientoEditar['TipoAsiento'] ?? '';
                            ?>

                            <select
                                name="TipoAsiento"
                                id="TipoAsiento"
                                class="form-select"
                                required
                            >
                                <option value="">
                                    Seleccione
                                </option>

                                <option
                                    value="Regular"
                                    <?= $tipoActual === 'Regular'
                                        ? 'selected'
                                        : ''
                                    ?>
                                >
                                    Regular
                                </option>

                                <option
                                    value="Preferencial"
                                    <?= $tipoActual === 'Preferencial'
                                        ? 'selected'
                                        : ''
                                    ?>
                                >
                                    Preferencial
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

                                <option
                                    value="Discapacidad"
                                    <?= $tipoActual === 'Discapacidad'
                                        ? 'selected'
                                        : ''
                                    ?>
                                >
                                    Discapacidad
                                </option>
                            </select>

                        </div>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-warning"
                    >
                        <?= $asientoEditar
                            ? 'Guardar cambios'
                            : 'Registrar asiento'
                        ?>
                    </button>

                    <?php if ($asientoEditar): ?>

                        <a
                            href="/WebCS_G6_Proyecto/View/Asiento.php"
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
            <strong>Asientos registrados</strong>
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
                        <th>Fila</th>
                        <th>Número</th>
                        <th>Tipo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                <?php if (empty($asientos)): ?>

                    <tr>
                        <td
                            colspan="7"
                            class="text-center py-4"
                        >
                            No hay asientos registrados.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($asientos as $asiento): ?>

                        <tr>
                            <td>
                                <?= (int) $asiento['ID_Asiento'] ?>
                            </td>

                            <td>
                                <?= EscaparAsiento(
                                    $asiento['NombreCine']
                                ) ?>
                            </td>

                            <td>
                                <?= EscaparAsiento(
                                    $asiento['NombreSala']
                                ) ?>
                            </td>

                            <td>
                                <?= EscaparAsiento(
                                    $asiento['Fila']
                                ) ?>
                            </td>

                            <td>
                                <?= (int) $asiento['Numero'] ?>
                            </td>

                            <td>
                                <?= EscaparAsiento(
                                    $asiento['TipoAsiento'] ?? ''
                                ) ?>
                            </td>

                            <td class="text-nowrap">

                                <a
                                    href="/WebCS_G6_Proyecto/View/Asiento.php?editar=<?= (int) $asiento['ID_Asiento'] ?>"
                                    class="btn btn-warning btn-sm"
                                >
                                    Editar
                                </a>

                                <form
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm(
                                        '¿Deseas eliminar este asiento?'
                                    );"
                                >
                                    <input
                                        type="hidden"
                                        name="accion"
                                        value="eliminar"
                                    >

                                    <input
                                        type="hidden"
                                        name="ID_Asiento"
                                        value="<?= (int) $asiento['ID_Asiento'] ?>"
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

<?php
Footer();
ImportJS();
?>

</body>
</html>