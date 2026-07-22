<?php

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Model/SalaModel.php';

function ObtenerSalasController()
{
    return ConsultarSalasModel();
}

function ObtenerCinesParaSalaController()
{
    return ConsultarCinesParaSalaModel();
}

function ObtenerSalaPorIdController($idSala)
{
    return ConsultarSalaPorIdModel($idSala);
}

function ProcesarSalaController()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $accion = $_POST['accion'] ?? '';

    try {
        switch ($accion) {
            case 'registrar':
                RegistrarSalaController();
                break;

            case 'actualizar':
                ActualizarSalaController();
                break;

            case 'eliminar':
                EliminarSalaController();
                break;
        }
    } catch (Throwable $error) {
        $_SESSION['mensajeSala'] =
            'Ocurrió un error: ' . $error->getMessage();

        $_SESSION['tipoMensajeSala'] = 'danger';
    }

    header(
        'Location: /WebCS_G6_Proyecto/View/Sala.php'
    );
    exit;
}

function RegistrarSalaController()
{
    $idCine = filter_input(
        INPUT_POST,
        'ID_Cine',
        FILTER_VALIDATE_INT
    );

    $nombre = trim($_POST['Nombre'] ?? '');

    $capacidad = filter_input(
        INPUT_POST,
        'Capacidad',
        FILTER_VALIDATE_INT
    );

    $tipoPantalla = $_POST['TipoPantalla'] ?? '';

    ValidarDatosSalaController(
        $idCine,
        $nombre,
        $capacidad,
        $tipoPantalla
    );

    RegistrarSalaModel(
    $idCine,
    $nombre,
    $capacidad,
    $tipoPantalla
    );

    $_SESSION['mensajeSala'] =
        'La sala fue registrada correctamente.';

    $_SESSION['tipoMensajeSala'] = 'success';
}

function ActualizarSalaController()
{
    $idSala = filter_input(
        INPUT_POST,
        'ID_Sala',
        FILTER_VALIDATE_INT
    );

    $idCine = filter_input(
        INPUT_POST,
        'ID_Cine',
        FILTER_VALIDATE_INT
    );

    $nombre = trim($_POST['Nombre'] ?? '');

    $capacidad = filter_input(
        INPUT_POST,
        'Capacidad',
        FILTER_VALIDATE_INT
    );

    $tipoPantalla = trim($_POST['TipoPantalla'] ?? '');

    if (!$idSala) {
        throw new Exception('El ID de la sala no es válido.');
    }

    ValidarDatosSalaController(
        $idCine,
        $nombre,
        $capacidad,
        $tipoPantalla
    );

    ActualizarSalaModel(
        $idSala,
        $idCine,
        $nombre,
        $capacidad,
        $tipoPantalla
    );

    $_SESSION['mensajeSala'] =
        'La sala fue actualizada correctamente.';

    $_SESSION['tipoMensajeSala'] = 'success';
}

function EliminarSalaController()
{
    $idSala = filter_input(
        INPUT_POST,
        'ID_Sala',
        FILTER_VALIDATE_INT
    );

    if (!$idSala) {
        throw new Exception('El ID de la sala no es válido.');
    }

    EliminarSalaModel($idSala);

    $_SESSION['mensajeSala'] =
        'La sala fue eliminada correctamente.';

    $_SESSION['tipoMensajeSala'] = 'success';
}

function ValidarDatosSalaController(
    $idCine,
    $nombre,
    $capacidad,
    $tipoPantalla
) {
    if (!$idCine) {
        throw new Exception('Debes seleccionar un cine.');
    }

    if ($nombre === '') {
        throw new Exception(
            'Debes ingresar el nombre de la sala.'
        );
    }

    if (!$capacidad || $capacidad <= 0) {
        throw new Exception(
            'La capacidad debe ser mayor que cero.'
        );
    }

    if ($tipoPantalla === '') {
        throw new Exception(
            'Debes indicar el tipo de pantalla.'
        );
    }
}