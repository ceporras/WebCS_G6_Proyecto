<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/Model/ClienteModel.php';

include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/Controller/UtilitarioController.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (isset($_POST["btnIniciarSesion"])) {

    $correo = trim($_POST["correo"]);
    $contrasenna = $_POST["contrasenna"];

    if (empty($correo) || empty($contrasenna)) {

        $_POST["Mensaje"] =
            "Debe completar el correo y la contraseña.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {

        $_POST["Mensaje"] =
            "Debe ingresar un correo electrónico válido.";
    } else {

        $datos = IniciarSesionModel(
            $correo,
            $contrasenna
        );

        if ($datos) {

            $_SESSION["ID_Cliente"] =
                $datos["ID_Cliente"];

            $_SESSION["Nombre"] =
                $datos["Nombre"]
                . " "
                . $datos["ApellidoPaterno"];

            $_SESSION["Correo"] =
                $datos["Correo"];

            $_SESSION["ID_Rol"] =
                $datos["ID_Rol"];

            header(
                "Location: ../View/index.php"
            );

            exit();
        } else {

            $_POST["Mensaje"] =
                "No se ha podido autenticar su información correctamente.";
        }
    }
}


if (isset($_POST["btnRegistrar"])) {

    $nombre =
        trim($_POST["nombre"]);

    $apellidoPaterno =
        trim($_POST["apellidoPaterno"]);

    $apellidoMaterno =
        trim($_POST["apellidoMaterno"]);

    $correo =
        trim($_POST["correo"]);

    $telefono =
        trim($_POST["telefono"]);

    $password =
        $_POST["password"];

    $confirmarPassword =
        $_POST["confirmarPassword"];

    if (
        empty($nombre) ||
        empty($apellidoPaterno) ||
        empty($apellidoMaterno) ||
        empty($correo) ||
        empty($telefono) ||
        empty($password) ||
        empty($confirmarPassword)
    ) {

        $_POST["Mensaje"] =
            "Debe completar todos los campos.";
    } elseif (
        !filter_var(
            $correo,
            FILTER_VALIDATE_EMAIL
        )
    ) {

        $_POST["Mensaje"] =
            "Debe ingresar un correo electrónico válido.";
    } elseif (
        !preg_match(
            '/^[0-9]{8}$/',
            $telefono
        )
    ) {

        $_POST["Mensaje"] =
            "El teléfono debe contener exactamente 8 números.";
    } elseif (
        strlen($password) < 6
    ) {

        $_POST["Mensaje"] =
            "La contraseña debe contener al menos 6 caracteres.";
    } elseif (
        $password != $confirmarPassword
    ) {

        $_POST["Mensaje"] =
            "Las contraseñas ingresadas no coinciden.";
    } else {

        $resultado =
            RegistrarUsuarioModel(
                $nombre,
                $apellidoPaterno,
                $apellidoMaterno,
                $correo,
                $telefono,
                $password
            );

        if ($resultado) {

            header(
                "Location: IniciarSesion.php?registro=exitoso"
            );

            exit();
        } else {

            $_POST["Mensaje"] =
                "No fue posible registrar el usuario. "
                . "El correo podría estar registrado.";
        }
    }
}


if (isset($_POST["btnRecuperarAcceso"])) {

    $correo =
        trim($_POST["correo"]);

    if (empty($correo)) {

        $_POST["Mensaje"] =
            "Debe ingresar su correo electrónico.";
    } elseif (
        !filter_var(
            $correo,
            FILTER_VALIDATE_EMAIL
        )
    ) {

        $_POST["Mensaje"] =
            "Debe ingresar un correo electrónico válido.";
    } else {

        $datos =
            ValidarCorreoModel(
                $correo
            );

        if ($datos) {

            $contrasennaTemporal =
                GenerarContrasenna();

            $actualizacion =
                ActualizarContrasennaModel(
                    $datos["ID_Cliente"],
                    $contrasennaTemporal
                );

            if ($actualizacion) {

                $rutaPlantilla =
                    $_SERVER['DOCUMENT_ROOT']
                    . '/WebCS_G6_Proyecto/' . 'View/templates/' . 'Recuperacion.html';

                if (!file_exists($rutaPlantilla)) {

                    $_POST["Mensaje"] =
                        "No se encontró la plantilla del correo.";
                } else {

                    $plantilla =
                        file_get_contents(
                            $rutaPlantilla
                        );

                    $plantilla =
                        str_replace(
                            "{{NOMBRE}}",
                            $datos["Nombre"],
                            $plantilla
                        );

                    $plantilla =
                        str_replace(
                            "{{TEMPORAL}}",
                            $contrasennaTemporal,
                            $plantilla
                        );

                    $correoEnviado =
                        EnviarCorreo(
                            "Recuperación de acceso - "
                                . "Golden Frame Cinema",
                            $plantilla,
                            $datos["Correo"]
                        );

                    if ($correoEnviado) {

                        header(
                            "Location: "
                                . "IniciarSesion.php"
                                . "?recuperacion=exitosa"
                        );

                        exit();
                    } else {

                        $_POST["Mensaje"] =
                            "La contraseña se actualizó, "
                            . "pero no se pudo enviar el correo.";
                    }
                }
            } else {

                $_POST["Mensaje"] =
                    "No se pudo generar la contraseña temporal.";
            }
        } else {

            $_POST["Mensaje"] =
                "No existe una cuenta activa asociada a ese correo.";
        }
    }
}



if (isset($_POST["btnSalir"])) {

    CerrarSesion();
}


function GetClientesCtrl()
{

    $datos = GetClientes();
    return $datos;
}

function GetClienteByIdCtrl()
{
    $ID_Cliente = $_SESSION["ID_Cliente"];
    $datos = GetClienteById($ID_Cliente);
    return $datos;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['EliminarUsuario'])) {

    $ID_Cliente = $_POST['ID_Cliente'];

    $resultado = DeleteCliente($ID_Cliente);

    if ($resultado) {

        echo json_encode([
            "success" => true
        ]);
    } else {

        echo json_encode([
            "success" => false,
            "message" => "No se pudo eliminar el usuario."
        ]);
    }

    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ObtenerCliente'])) {

    $ID_Cliente = $_POST['ID_Cliente'];

    $cliente = GetClienteById($ID_Cliente);

    if ($cliente) {

        echo json_encode([
            "success" => true,
            "cliente" => $cliente
        ]);
    } else {

        echo json_encode([
            "success" => false,
            "message" => "No se encontró el usuario."
        ]);
    }

    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ActualizarCliente'])) {

    $ID_Cliente      = $_POST['ID_Cliente'];
    $Nombre          = $_POST['Nombre'];
    $ApellidoPaterno = $_POST['ApellidoPaterno'];
    $ApellidoMaterno = $_POST['ApellidoMaterno'];
    $Correo          = $_POST['Correo'];
    $Telefono        = $_POST['Telefono'];
    $Estado          = (int) $_POST['Estado'];
    $ID_Rol             = (int) $_POST['ID_Rol'];

    $resultado = UpdateCliente(
        $ID_Cliente,
        $Nombre,
        $ApellidoPaterno,
        $ApellidoMaterno,
        $Correo,
        $Telefono,
        $Estado,
        $ID_Rol
    );

    if ($resultado) {

        echo json_encode([
            "success" => true,
            "message" => "Usuario actualizado correctamente."
        ]);
    } else {

        echo json_encode([
            "success" => false,
            "message" => "No se pudo actualizar el usuario."
        ]);
    }

    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['CrearCliente'])) {

    $Nombre          = $_POST['Nombre'];
    $ApellidoPaterno = $_POST['ApellidoPaterno'];
    $ApellidoMaterno = $_POST['ApellidoMaterno'];
    $Correo          = $_POST['Correo'];
    $Telefono        = $_POST['Telefono'];
    $Estado          = (int) $_POST['Estado'];
    $ID_Rol          = (int) $_POST['ID_Rol'];
    $Password        = $_POST['Password'];

    $resultado = CrearCliente(
        $Nombre,
        $ApellidoPaterno,
        $ApellidoMaterno,
        $Correo,
        $Telefono,
        $Estado,
        $ID_Rol,
        $Password
    );

    if ($resultado["success"]) {

        echo json_encode([
            "success" => true
        ]);
    } else {

        if ($resultado["code"] == 1062) {

            echo json_encode([
                "success" => false,
                "message" => "Ya existe un usuario con ese correo electrónico."
            ]);
        } else {

            echo json_encode([
                "success" => false,
                "message" => "No se pudo crear el usuario."
            ]);
        }
    }

    exit;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ObtenerMiPerfil'])) {

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['ID_Cliente'])) {

        echo json_encode([
            "success" => false,
            "message" => "La sesión ha expirado."
        ]);

        exit;
    }

    $ID_Cliente = $_SESSION['ID_Cliente'];

    $cliente = GetClienteById($ID_Cliente);

    if ($cliente) {

        echo json_encode([
            "success" => true,
            "cliente" => $cliente
        ]);
    } else {

        echo json_encode([
            "success" => false,
            "message" => "No se encontró la información del usuario."
        ]);
    }

    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ActualizarMiPerfil'])) {

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['ID_Cliente'])) {

        echo json_encode([
            "success" => false,
            "message" => "La sesión ha expirado."
        ]);

        exit;
    }

    $ID_Cliente = $_SESSION['ID_Cliente'];

    $Nombre          = trim($_POST['Nombre'] ?? '');
    $ApellidoPaterno = trim($_POST['ApellidoPaterno'] ?? '');
    $ApellidoMaterno = trim($_POST['ApellidoMaterno'] ?? '');
    $Correo          = trim($_POST['Correo'] ?? '');
    $Telefono        = trim($_POST['Telefono'] ?? '');


    if (
        empty($Nombre) ||
        empty($ApellidoPaterno) ||
        empty($ApellidoMaterno) ||
        empty($Correo) ||
        empty($Telefono)
    ) {

        echo json_encode([
            "success" => false,
            "message" => "Todos los campos son obligatorios."
        ]);

        exit;
    }


    if (!filter_var($Correo, FILTER_VALIDATE_EMAIL)) {

        echo json_encode([
            "success" => false,
            "message" => "El correo electrónico no es válido."
        ]);

        exit;
    }


    $resultado = UpdateClientePerfil(
        $ID_Cliente,
        $Nombre,
        $ApellidoPaterno,
        $ApellidoMaterno,
        $Correo,
        $Telefono
    );


    echo json_encode($resultado);

    exit;
}


if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['CambiarPassword'])
) {

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['ID_Cliente'])) {

        echo json_encode([
            "success" => false,
            "message" => "La sesión ha expirado."
        ]);

        exit;
    }

    $ID_Cliente = $_SESSION['ID_Cliente'];

    $PasswordActual = $_POST['PasswordActual'] ?? '';
    $PasswordNueva  = $_POST['PasswordNueva'] ?? '';


    if (empty($PasswordActual) || empty($PasswordNueva)) {

        echo json_encode([
            "success" => false,
            "message" => "Todos los campos son obligatorios."
        ]);

        exit;
    }


    if (strlen($PasswordNueva) < 6) {

        echo json_encode([
            "success" => false,
            "message" => "La nueva contraseña debe tener al menos 6 caracteres."
        ]);

        exit;
    }


    // Get current user
    $cliente = GetClientePwdById($ID_Cliente);


    if (!$cliente) {
        echo json_encode([
            "success" => false,
            "message" => "No se encontró el usuario."
        ]);
        exit;
    }

    // Verify current password (plain text)
    if ($PasswordActual !== $cliente['Password']) {
        echo json_encode([
            "success" => false,
            "message" => "La contraseña actual es incorrecta."
        ]);
        exit;
    }

    // Save the new password directly
    $resultado = UpdatePassword($ID_Cliente, $PasswordNueva);

    echo json_encode($resultado);
    exit;
}
