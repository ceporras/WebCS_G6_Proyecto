<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/View/IntLayout.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/View/ExtLayout.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/Controller/ClienteController.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$usuarios = GetClientesCtrl();
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

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Usuarios registrados</h2>
                <p class="text-muted mb-0">
                    Lista de usuarios del sistema
                </p>
            </div>

            <div class="d-flex gap-2">
        <button
            type="button"
            id="btnNuevoUsuario"
            class="btn btn-dorado">
            <i class="bi bi-plus-lg"></i> Nuevo Usuario
        </button>

    </div>
        </div>

        <?php if (!empty($usuarios)): ?>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-dark">
                                <tr>
                                    <th>ID Usuario</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Correo</th>
                                    <th>Teléfono</th>
                                    <th>Estado</th>
                                    <th>Rol</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php foreach ($usuarios as $usuario): ?>

                                    <tr>

                                        <td>
                                            <strong>
                                                #<?= htmlspecialchars($usuario['ID_Cliente']) ?>
                                            </strong>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($usuario['Nombre']) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars(
                                                $usuario['ApellidoPaterno'] . ' ' .
                                                    $usuario['ApellidoMaterno']
                                            ) ?>
                                        </td>

                                        <td>
                                            <a href="mailto:<?= htmlspecialchars($usuario['Correo']) ?>"
                                                class="text-decoration-none">
                                                <?= htmlspecialchars($usuario['Correo']) ?>
                                            </a>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($usuario['Telefono']) ?>
                                        </td>

                                        <td>
                                            <?php if ((int)$usuario['Estado'] === 1): ?>

                                                <span class="badge bg-success">
                                                    Activo
                                                </span>

                                            <?php else: ?>

                                                <span class="badge bg-danger">
                                                    Inactivo
                                                </span>

                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <span class="badge bg-info text-dark">
                                                <?= htmlspecialchars($usuario['Rol']) ?>
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">

                                                <button
                                                    type="button"
                                                    name="EditarUsuario"
                                                    class="btn btn-sm btn-warning btn-editarCliente"
                                                    data-id="<?= htmlspecialchars($usuario['ID_Cliente']) ?>">
                                                    Editar
                                                </button>


                                                <button
                                                    type="button" name="btnBorrarCliente"
                                                    class="btn btn-sm btn-danger btn-eliminarCliente"
                                                    data-id="<?= htmlspecialchars($usuario['ID_Cliente']) ?>">
                                                    Eliminar
                                                </button>



                                            </div>
                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

        <?php else: ?>

            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">

                    <h5 class="mb-2">No hay usuarios registrados</h5>

                    <p class="text-muted mb-0">
                        No existen usuarios registrados en el sistema.
                    </p>

                </div>
            </div>

        <?php endif; ?>

    </main>
    <?php
    Footer();
    ImportJS();
    ?>
</body>

</html>