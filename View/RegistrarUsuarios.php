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
                    <h1 class="card-title mb-5 h5">Registrar Usuarios</h1>
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

<form action="" method="post" class="needs-validation mt-3" novalidate>

    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input
            id="nombre"
            name="nombre"
            type="text"
            class="form-control"
            maxlength="45"
            required
        />
        <div class="invalid-feedback">
            Ingrese su nombre.
        </div>
    </div>

    <div class="mb-3">
        <label for="apellidoPaterno" class="form-label">
            Primer apellido
        </label>
        <input
            id="apellidoPaterno"
            name="apellidoPaterno"
            type="text"
            class="form-control"
            maxlength="45"
            required
        />
        <div class="invalid-feedback">
            Ingrese su primer apellido.
        </div>
    </div>

    <div class="mb-3">
        <label for="apellidoMaterno" class="form-label">
            Segundo apellido
        </label>
        <input
            id="apellidoMaterno"
            name="apellidoMaterno"
            type="text"
            class="form-control"
            maxlength="45"
            required
        />
        <div class="invalid-feedback">
            Ingrese su segundo apellido.
        </div>
    </div>

    <div class="mb-3">
        <label for="correo" class="form-label">
            Correo electrónico
        </label>
        <input
            id="correo"
            name="correo"
            type="email"
            class="form-control"
            maxlength="45"
            required
        />
        <div class="invalid-feedback">
            Ingrese un correo electrónico válido.
        </div>
    </div>

    <div class="mb-3">
        <label for="telefono" class="form-label">Teléfono</label>
        <input
            id="telefono"
            name="telefono"
            type="text"
            class="form-control"
            maxlength="45"
            pattern="[0-9]{8}"
            placeholder="88888888"
            required
        />
        <div class="invalid-feedback">
            Ingrese un número de teléfono de 8 dígitos.
        </div>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input
            id="password"
            name="password"
            type="password"
            class="form-control"
            minlength="6"
            maxlength="150"
            required
        />
        <div class="invalid-feedback">
            La contraseña debe contener al menos 6 caracteres.
        </div>
    </div>

    <div class="mb-3">
        <label for="confirmarPassword" class="form-label">
            Confirmar contraseña
        </label>
        <input
            id="confirmarPassword"
            name="confirmarPassword"
            type="password"
            class="form-control"
            minlength="6"
            maxlength="150"
            required
        />
        <div class="invalid-feedback">
            Confirme su contraseña.
        </div>
    </div>

    <button
        class="btn btn-primary w-100"
        type="submit"
        name="btnRegistrar"
        id="btnRegistrar"
    >
        Registrarse
    </button>

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