<?php

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Model/AsientoModel.php';

function ObtenerAsientosController()
{
    return ConsultarAsientosModel();
}

function ObtenerSalasParaAsientoController()
{
    return ConsultarSalasParaAsientoModel();
}

function ObtenerAsientoPorIdController($idAsiento)
{
    return ConsultarAsientoPorIdModel($idAsiento);
}

function ProcesarAsientoController()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $accion = $_POST['accion'] ?? '';

    try {
        switch ($accion) {
            case 'registrar':
                RegistrarAsientoController();
                break;

            case 'actualizar':
                ActualizarAsientoController();
                break;

            case 'eliminar':
                EliminarAsientoController();
                break;
        }
    } catch (Throwable $error) {
        $_SESSION['mensajeAsiento'] =
            'Ocurrió un error: ' . $error->getMessage();

        $_SESSION['tipoMensajeAsiento'] = 'danger';
    }

    header(
        'Location: /WebCS_G6_Proyecto/View/Asiento.php'
    );

    exit;
}

function RegistrarAsientoController()
{
    $idSala = filter_input(
        INPUT_POST,
        'ID_Sala',
        FILTER_VALIDATE_INT
    );

    $fila = strtoupper(
        trim($_POST['Fila'] ?? '')
    );

    $numero = filter_input(
        INPUT_POST,
        'Numero',
        FILTER_VALIDATE_INT
    );

    $tipoAsiento = trim(
        $_POST['TipoAsiento'] ?? ''
    );

    ValidarDatosAsientoController(
        $idSala,
        $fila,
        $numero,
        $tipoAsiento
    );

    RegistrarAsientoModel(
        $idSala,
        $fila,
        $numero,
        $tipoAsiento
    );

    $_SESSION['mensajeAsiento'] =
        'El asiento fue registrado correctamente.';

    $_SESSION['tipoMensajeAsiento'] = 'success';
}

function ActualizarAsientoController()
{
    $idAsiento = filter_input(
        INPUT_POST,
        'ID_Asiento',
        FILTER_VALIDATE_INT
    );

    $idSala = filter_input(
        INPUT_POST,
        'ID_Sala',
        FILTER_VALIDATE_INT
    );

    $fila = strtoupper(
        trim($_POST['Fila'] ?? '')
    );

    $numero = filter_input(
        INPUT_POST,
        'Numero',
        FILTER_VALIDATE_INT
    );

    $tipoAsiento = trim(
        $_POST['TipoAsiento'] ?? ''
    );

    if (!$idAsiento) {
        throw new Exception(
            'El ID del asiento no es válido.'
        );
    }

    ValidarDatosAsientoController(
        $idSala,
        $fila,
        $numero,
        $tipoAsiento
    );

    ActualizarAsientoModel(
        $idAsiento,
        $idSala,
        $fila,
        $numero,
        $tipoAsiento
    );

    $_SESSION['mensajeAsiento'] =
        'El asiento fue actualizado correctamente.';

    $_SESSION['tipoMensajeAsiento'] = 'success';
}

function EliminarAsientoController()
{
    $idAsiento = filter_input(
        INPUT_POST,
        'ID_Asiento',
        FILTER_VALIDATE_INT
    );

    if (!$idAsiento) {
        throw new Exception(
            'El ID del asiento no es válido.'
        );
    }

    EliminarAsientoModel($idAsiento);

    $_SESSION['mensajeAsiento'] =
        'El asiento fue eliminado correctamente.';

    $_SESSION['tipoMensajeAsiento'] = 'success';
}

function ValidarDatosAsientoController(
    $idSala,
    $fila,
    $numero,
    $tipoAsiento
) {
    if (!$idSala) {
        throw new Exception(
            'Debes seleccionar una sala.'
        );
    }

    if ($fila === '') {
        throw new Exception(
            'Debes ingresar la fila.'
        );
    }

    if (strlen($fila) > 10) {
        throw new Exception(
            'La fila no puede superar 10 caracteres.'
        );
    }

    if (!$numero || $numero <= 0) {
        throw new Exception(
            'El número debe ser mayor que cero.'
        );
    }

    if ($tipoAsiento === '') {
        throw new Exception(
            'Debes seleccionar el tipo de asiento.'
        );
    }
}