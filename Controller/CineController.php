<?php

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Model/CineModel.php';

/* Registrar cine */

if (isset($_POST['btnRegistrarCine'])) {
    try {
        $nombre = trim($_POST['nombre'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $ciudad = trim($_POST['ciudad'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $correo = trim($_POST['correo'] ?? '');

        if (
            $nombre === '' ||
            $direccion === '' ||
            $ciudad === '' ||
            $telefono === '' ||
            $correo === ''
        ) {
            throw new Exception(
                'Todos los campos del cine son obligatorios.'
            );
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            throw new Exception(
                'El correo ingresado no es válido.'
            );
        }

        RegistrarCineModel(
            $nombre,
            $direccion,
            $ciudad,
            $telefono,
            $correo
        );

        $_SESSION['Mensaje'] =
            'El cine fue registrado correctamente.';

        $_SESSION['TipoMensaje'] = 'success';
    } catch (Throwable $e) {
        $_SESSION['Mensaje'] = $e->getMessage();
        $_SESSION['TipoMensaje'] = 'danger';
    }

    header(
        'Location: /WebCS_G6_Proyecto/View/Cine.php'
    );
    exit;
}

/* Actualizar cine */

if (isset($_POST['btnActualizarCine'])) {
    try {
        $idCine = filter_input(
            INPUT_POST,
            'idCine',
            FILTER_VALIDATE_INT
        );

        $nombre = trim($_POST['nombre'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $ciudad = trim($_POST['ciudad'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $correo = trim($_POST['correo'] ?? '');

        if (
            !$idCine ||
            $nombre === '' ||
            $direccion === '' ||
            $ciudad === '' ||
            $telefono === '' ||
            $correo === ''
        ) {
            throw new Exception(
                'Los datos del cine son inválidos.'
            );
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            throw new Exception(
                'El correo ingresado no es válido.'
            );
        }

        ActualizarCineModel(
            $idCine,
            $nombre,
            $direccion,
            $ciudad,
            $telefono,
            $correo
        );

        $_SESSION['Mensaje'] =
            'El cine fue actualizado correctamente.';

        $_SESSION['TipoMensaje'] = 'success';
    } catch (Throwable $e) {
        $_SESSION['Mensaje'] = $e->getMessage();
        $_SESSION['TipoMensaje'] = 'danger';
    }

    header(
        'Location: /WebCS_G6_Proyecto/View/Cine.php'
    );
    exit;
}

/* Eliminar cine */

if (isset($_POST['btnEliminarCine'])) {
    try {
        $idCine = filter_input(
            INPUT_POST,
            'idCine',
            FILTER_VALIDATE_INT
        );

        if (!$idCine) {
            throw new Exception(
                'El ID del cine no es válido.'
            );
        }

        EliminarCineModel($idCine);

        $_SESSION['Mensaje'] =
            'El cine fue eliminado correctamente.';

        $_SESSION['TipoMensaje'] = 'success';
    } catch (Throwable $e) {
        $_SESSION['Mensaje'] =
            'No se pudo eliminar el cine. Puede tener salas asociadas.';

        $_SESSION['TipoMensaje'] = 'danger';
    }

    header(
        'Location: /WebCS_G6_Proyecto/View/Cine.php'
    );
    exit;
}