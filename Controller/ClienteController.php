<?php

include_once $_SERVER['DOCUMENT_ROOT']. '/WebCS_G6_Proyecto/Model/ClienteModel.php';

include_once $_SERVER['DOCUMENT_ROOT']. '/WebCS_G6_Proyecto/Controller/UtilitarioController.php';

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
                    . '/WebCS_G6_Proyecto/'. 'View/templates/'. 'Recuperacion.html';

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