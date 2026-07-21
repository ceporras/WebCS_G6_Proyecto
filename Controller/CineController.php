<?php

include_once $_SERVER['DOCUMENT_ROOT']
. '/WebCS_G6_Proyecto/Model/CineModel.php';

/* REGISTRAR CINE */

if (isset($_POST['btnRegistrarCine'])) {

    try {

        $nombre = trim($_POST['nombre'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $ciudad = trim($_POST['ciudad'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $correo = trim($_POST['correo'] ?? '');

        if (
            $nombre == '' ||
            $direccion == '' ||
            $ciudad == '' ||
            $telefono == '' ||
            $correo == ''
        ) {
            throw new Exception('Todos los campos son obligatorios.');
        }

        RegistrarCineModel(
            $nombre,
            $direccion,
            $ciudad,
            $telefono,
            $correo
        );

        $_SESSION['Mensaje'] = 'El cine fue registrado correctamente.';
        $_SESSION['TipoMensaje'] = 'success';

    } catch (Throwable $e) {

        $_SESSION['Mensaje'] = 'No fue posible registrar el cine.';
        $_SESSION['TipoMensaje'] = 'danger';

    }

    header('Location: ../View/Cines.php');
    exit;
}

/* ACTUALIZAR CINE */

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
            $nombre == '' ||
            $direccion == '' ||
            $ciudad == '' ||
            $telefono == '' ||
            $correo == ''
        ) {
            throw new Exception('Datos inválidos.');
        }

        ActualizarCineModel(
            $idCine,
            $nombre,
            $direccion,
            $ciudad,
            $telefono,
            $correo
        );

        $_SESSION['Mensaje'] = 'El cine fue actualizado correctamente.';
        $_SESSION['TipoMensaje'] = 'success';

    } catch (Throwable $e) {

        $_SESSION['Mensaje'] = 'No fue posible actualizar el cine.';
        $_SESSION['TipoMensaje'] = 'danger';

    }

    header('Location: ../View/Cines.php');
    exit;
}

/* ELIMINAR CINE */

if (isset($_POST['btnEliminarCine'])) {

    try {

        $idCine = filter_input(
            INPUT_POST,
            'idCine',
            FILTER_VALIDATE_INT
        );

        if (!$idCine) {
            throw new Exception('ID inválido.');
        }

        EliminarCineModel($idCine);

        $_SESSION['Mensaje'] = 'El cine fue eliminado correctamente.';
        $_SESSION['TipoMensaje'] = 'success';

    } catch (Throwable $e) {

        $_SESSION['Mensaje'] = 'No fue posible eliminar el cine.';
        $_SESSION['TipoMensaje'] = 'danger';

    }

    header('Location: ../View/Cines.php');
    exit;
}