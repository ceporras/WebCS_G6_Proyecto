<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/View/ExtLayout.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/Controller/ClienteController.php';
?>

<!doctype html>
<html lang="es">

<?php
ImportCSS();
?>

<body>

    <div class="container d-flex align-items-center justify-content-center min-vh-100">

        <div class="card" style="max-width:420px; width:100%;">

            <div class="card-body p-5">

                <div class="text-center mb-3">

                    <a href="index.php" class="mb-2 d-inline-block">
                        <img src="images/logo_golden_frame-rembg-preview.png"
                            width="180">
                    </a>

                    <h1 class="card-title mb-3 h5">
                        Recuperar Acceso
                    </h1>

                    <p class="text-muted">
                        Ingrese el correo asociado a su cuenta.
                    </p>

                </div>

                <?php
                if (isset($_POST["Mensaje"])) {
                    echo '<div class="alert alert-warning text-center">'
                        . $_POST["Mensaje"] .
                        '</div>';
                }
                ?>

                <form action="" method="POST" class="needs-validation mt-3" novalidate>

                    <div class="mb-3">

                        <label class="form-label">
                            Correo Electrónico
                        </label>

                        <input
                            id="correoElectronico"
                            name="correo"
                            type="email"
                            class="form-control"
                            placeholder="correo@ejemplo.com"
                            required
                            autofocus>

                        <div class="invalid-feedback">
                            Debe ingresar un correo electrónico válido.
                        </div>

                    </div>

                    <button
                        class="btn btn-primary w-100"
                        type="submit"
                        name="btnRecuperarAcceso">

                        Procesar

                    </button>

                </form>

                <hr>

                <div class="text-center">

                    <a href="IniciarSesion.php" class="link-primary">
                        Volver al inicio de sesión
                    </a>

                </div>

                <div class="text-center mt-3 small text-muted">

                    ¿No tiene una cuenta?

                    <a href="RegistrarUsuarios.php" class="link-primary">
                        Regístrese
                    </a>

                </div>

            </div>

        </div>

    </div>

</body>

</html>