<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/View/ExtLayout.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/Controller/ClienteController.php';
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
          <h1 class="card-title mb-5 h5">Iniciar Sesión</h1>
        </div>

                <?php
        if (isset($_POST["Mensaje"])) {
        ?>
            <div class="alert alert-danger text-center">
                <?php echo $_POST["Mensaje"]; ?>
            </div>
        <?php
        }
        ?>
        <?php
          if (
              isset($_GET["registro"]) &&
              $_GET["registro"] == "exitoso"
          ) {
          ?>
              <div class="alert alert-success text-center">
                  El usuario se registró correctamente. Ya puede iniciar sesión.
              </div>
          <?php
          }
          ?>

        <form action="" method="post" class="needs-validation mt-3" id="formIniciarSesion">

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo electrónico</label>
                        <input id="correo" name="correo" type="email" class="form-control" autofocus />
                    </div>

                    <div class="mb-3">
                        <label for="contrasenna" class="form-label d-flex justify-content-between">
                            <span>Contraseña</span>
                            <a href="RecuperarAcceso.php" class="small link-primary">¿Olvidó su contraseña?</a>
                        </label>
                        <input id="contrasenna" name="contrasenna" type="password" class="form-control" />
                    </div>

                    <button type="submit" id="btnIniciarSesion" name="btnIniciarSesion" class="btn btn-primary w-100">Procesar</button>
                </form>

        <div class="text-center mt-3 small text-muted">
          ¿No tiene una cuenta?
          <a href="RegistrarUsuarios.php" class="link-primary">Regístrese</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
</body>

</html>