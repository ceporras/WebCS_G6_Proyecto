<?php

require_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Model/FuncionModel.php';


function responderJson($exito, $mensaje, $datos = null, $codigo = 200)
{
    http_response_code($codigo);
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode([
        'exito' => $exito,
        'mensaje' => $mensaje,
        'datos' => $datos
    ], JSON_UNESCAPED_UNICODE);

    exit;
}


function obtenerDato($nombre, $valorPredeterminado = null)
{
    $valor = $_POST[$nombre]
        ?? $_GET[$nombre]
        ?? $valorPredeterminado;

    return is_string($valor) ? trim($valor) : $valor;
}


function validarId($valor, $campo)
{
    if (
        filter_var($valor, FILTER_VALIDATE_INT) === false
        || (int) $valor <= 0
    ) {
        throw new InvalidArgumentException(
            'El campo ' . $campo . ' no es válido.'
        );
    }

    return (int) $valor;
}


function validarPrecio($valor)
{
    if (!is_numeric($valor) || (float) $valor <= 0) {
        throw new InvalidArgumentException(
            'Debe ingresar un precio mayor que cero.'
        );
    }

    return (float) $valor;
}


function validarTexto($valor, $campo, $maximo = 45)
{
    $valor = trim((string) $valor);

    if ($valor === '') {
        throw new InvalidArgumentException(
            'Debe ingresar el campo ' . $campo . '.'
        );
    }

    if (mb_strlen($valor) > $maximo) {
        throw new InvalidArgumentException(
            'El campo ' . $campo
            . ' no puede superar los '
            . $maximo
            . ' caracteres.'
        );
    }

    return $valor;
}


function validarFecha($valor)
{
    $valor = trim((string) $valor);

    if ($valor === '') {
        throw new InvalidArgumentException(
            'Debe ingresar la fecha y hora de inicio.'
        );
    }

    $valor = str_replace('T', ' ', $valor);

    if (strlen($valor) === 16) {
        $valor .= ':00';
    }

    $fecha = DateTime::createFromFormat('Y-m-d H:i:s', $valor);
    $errores = DateTime::getLastErrors();

    if (
        !$fecha
        || (
            is_array($errores)
            && (
                $errores['warning_count'] > 0
                || $errores['error_count'] > 0
            )
        )
    ) {
        throw new InvalidArgumentException(
            'La fecha y hora no tienen un formato válido.'
        );
    }

    return $fecha->format('Y-m-d H:i:s');
}


function validarMetodoPost($mensaje)
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        responderJson(false, $mensaje, null, 405);
    }
}


function obtenerDatosFuncion($incluirId = false)
{
    $datos = [];

    if ($incluirId) {
        $datos['idFuncion'] = validarId(
            obtenerDato('idFuncion'),
            'identificador de la función'
        );
    }

    $datos['idPelicula'] = validarId(
        obtenerDato('idPelicula'),
        'película'
    );

    $datos['idSala'] = validarId(
        obtenerDato('idSala'),
        'sala'
    );

    $datos['horaInicio'] = validarFecha(
        obtenerDato('horaInicio')
    );

    $datos['precio'] = validarPrecio(
        obtenerDato('precio')
    );

    $datos['idioma'] = validarTexto(
        obtenerDato('idioma'),
        'idioma'
    );

    $datos['formato'] = validarTexto(
        obtenerDato('formato'),
        'formato'
    );

    return $datos;
}


function ejecutarAccion()
{
    $accion = obtenerDato('accion', '');

    if ($accion === '') {
        responderJson(
            false,
            'No se indicó ninguna acción.',
            null,
            400
        );
    }

    try {
        switch ($accion) {
            case 'consultarFunciones':
                responderJson(
                    true,
                    'Funciones consultadas correctamente.',
                    ConsultarFuncionesModel()
                );
                break;


            case 'consultarFuncionPorId':
                $idFuncion = validarId(
                    obtenerDato('idFuncion'),
                    'identificador de la función'
                );

                $funcion = ConsultarFuncionPorIdModel($idFuncion);

                if (!$funcion) {
                    responderJson(
                        false,
                        'La función solicitada no existe.',
                        null,
                        404
                    );
                }

                responderJson(
                    true,
                    'Función consultada correctamente.',
                    $funcion
                );
                break;


            case 'consultarPeliculas':
                responderJson(
                    true,
                    'Películas consultadas correctamente.',
                    ConsultarPeliculasActivasFuncionModel()
                );
                break;


            case 'consultarCines':
                responderJson(
                    true,
                    'Cines consultados correctamente.',
                    ConsultarCinesFuncionModel()
                );
                break;


            case 'consultarSalas':
                $idCine = validarId(
                    obtenerDato('idCine'),
                    'identificador del cine'
                );

                responderJson(
                    true,
                    'Salas consultadas correctamente.',
                    ConsultarSalasPorCineFuncionModel($idCine)
                );
                break;


            case 'registrarFuncion':
                validarMetodoPost(
                    'El registro debe realizarse mediante POST.'
                );

                $datos = obtenerDatosFuncion();

                $respuesta = RegistrarFuncionModel(
                    $datos['idPelicula'],
                    $datos['idSala'],
                    $datos['horaInicio'],
                    $datos['precio'],
                    $datos['idioma'],
                    $datos['formato']
                );

                responderJson(
                    true,
                    $respuesta['Mensaje']
                        ?? 'Función registrada correctamente.',
                    $respuesta
                );
                break;


            case 'actualizarFuncion':
                validarMetodoPost(
                    'La actualización debe realizarse mediante POST.'
                );

                $datos = obtenerDatosFuncion(true);

                $respuesta = ActualizarFuncionModel(
                    $datos['idFuncion'],
                    $datos['idPelicula'],
                    $datos['idSala'],
                    $datos['horaInicio'],
                    $datos['precio'],
                    $datos['idioma'],
                    $datos['formato']
                );

                responderJson(
                    true,
                    $respuesta['Mensaje']
                        ?? 'Función actualizada correctamente.',
                    $respuesta
                );
                break;


            case 'eliminarFuncion':
                validarMetodoPost(
                    'La eliminación debe realizarse mediante POST.'
                );

                $idFuncion = validarId(
                    obtenerDato('idFuncion'),
                    'identificador de la función'
                );

                $respuesta = EliminarFuncionModel($idFuncion);

                responderJson(
                    true,
                    $respuesta['Mensaje']
                        ?? 'Función eliminada correctamente.',
                    $respuesta
                );
                break;


            default:
                responderJson(
                    false,
                    'La acción solicitada no es válida.',
                    null,
                    400
                );
        }
    } catch (InvalidArgumentException $ex) {
        responderJson(
            false,
            $ex->getMessage(),
            null,
            400
        );
    } catch (mysqli_sql_exception $ex) {
        responderJson(
            false,
            $ex->getMessage(),
            null,
            400
        );
    } catch (Throwable $ex) {
        responderJson(
            false,
            'Ocurrió un error al procesar la solicitud: '
                . $ex->getMessage(),
            null,
            500
        );
    }
}


if (
    realpath(__FILE__) ===
    realpath($_SERVER['SCRIPT_FILENAME'] ?? '')
) {
    ejecutarAccion();
}