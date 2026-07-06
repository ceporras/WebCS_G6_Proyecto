<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/WebCS_G6_Proyecto/View/ExtLayout.php';
?>

<!doctype html>
<html lang="en">

<?php
  ImportCSS();
?>

<body>
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="card" style="max-width: 420px; width: 100%">
            <div class="card-body p-5">
                <div class="text-center mb-3">
                      <a href="index.php" class="mb-2 d-inline-block"><img src="images/logo_golden_frame-removebg-preview.png" alt="" 
            width="180" />
          </a>
                    <h1 class="card-title mb-5 h5">Registrar Usuarios</h1>
                </div>

                <form class="needs-validation mt-3" novalidate>
                    <div class="mb-3">
                        <label for="identificacion" class="form-label">Identificación</label>
                        <input id="identificacion" type="text" class="form-control" required />
                    </div>

                    <div class="mb-3">
                     <label for="nombre class="form-label">Nombre</label>
                        <input id="nombre" type="text" class="form-control" required />
                    </div>

                    <div class="mb-3">
                        <label for="correoElectronico" class="form-label">Correo Electrónico</label>
                        <input id="correoElectronico" type="text" class="form-control" required />
                    </div>

                    <div class="mb-3">
                        <label for="contrasenna" class="form-label">Contraseña</label>
                        <input id="contrasenna" type="password" class="form-control" required minlength="6" />
                    </div>

                    <button class="btn btn-primary w-100" type="submit">Procesar</button>
                </form>

                <div class="text-center mt-3 small text-muted">
                    ¿Ya tienes una cuenta?
                    <a href="IniciarSesion.php" class="link-primary">Inicie Sesión</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    
    <?php
        ImportJS();
    ?>
</body>

</html>