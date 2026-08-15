<?php

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/ExtLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/IntLayout.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//no puedo estar aqui sin login
if (!isset($_SESSION['ID_Cliente'])) {
    header("Location: ../View/index.php");
    exit;
}

$ID_Cliente = $_SESSION['ID_Cliente'];

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

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow-sm">

                <div class="card-body p-4">

                    <h2 class="mb-1">Mi perfil</h2>

                    <p class="text-muted mb-4">
                        Administra tu información personal y contraseña.
                    </p>

                    <!-- Información personal -->

                    <h5 class="mb-3">
                        Información personal
                    </h5>

                    <form id="formPerfil">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Nombre
                                </label>

                                <input
                                    type="text"
                                    id="perfil-nombre"
                                    class="form-control"
                                    maxlength="45">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Apellido paterno
                                </label>

                                <input
                                    type="text"
                                    id="perfil-apellido-paterno"
                                    class="form-control"
                                    maxlength="45">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Apellido materno
                                </label>

                                <input
                                    type="text"
                                    id="perfil-apellido-materno"
                                    class="form-control"
                                    maxlength="45">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Correo
                                </label>

                                <input
                                    type="email"
                                    id="perfil-correo"
                                    class="form-control"
                                    maxlength="45">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Teléfono
                                </label>

                                <input
                                    type="text"
                                    id="perfil-telefono"
                                    class="form-control"
                                    maxlength="45">
                            </div>

                        </div>

                        <button
                            type="button"
                            id="btnGuardarPerfil"
                            class="btn btn-primary">
                            Guardar cambios
                        </button>

                    </form>

                    <hr class="my-5">

                    <!-- Password -->

                    <h5 class="mb-3">
                        Cambiar contraseña
                    </h5>

                    <button
                        type="button"
                        id="btnCambiarPassword"
                        class="btn btn-warning">
                        Cambiar contraseña
                    </button>

                </div>

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