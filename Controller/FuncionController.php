<?php

require_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Model/FuncionModel.php';


/**
 * 
 *
 * @param bool $exito
 * @param string $mensaje
 * @param mixed $datos
 * @param int $codigoHttp
 * @return void
 */
function ResponderJsonFuncionController(
    $exito,
    $mensaje,
    $datos = null,
    $codigoHttp = 200
) {
    http_response_code($codigoHttp);

    header('Content-Type: application/json; charset=utf-8');

    echo json_encode(
        [
            'exito' => (bool) $exito,
            'mensaje' => $mensaje,
            'datos' => $datos
        ],
        JSON_UNESCAPED_UNICODE
    );

    exit;
}


/**
 * 
 *
 * @param string $nombre
 * @param mixed $valorPredeterminado
 * @return mixed
 */
function ObtenerPostFuncionController(
    $nombre,
    $valorPredeterminado = null
) {
    if (!isset($_POST[$nombre])) {
        return $valorPredeterminado;
    }

    if (is_string($_POST[$nombre])) {
        return trim($_POST[$nombre]);
    }

    return $_POST[$nombre];
}


/**
 * 
 *
 * @param string $nombre
 * @param mixed $valorPredeterminado
 * @return mixed
 */
function ObtenerGetFuncionController(
    $nombre,
    $valorPredeterminado = null
) {
    if (!isset($_GET[$nombre])) {
        return $valorPredeterminado;
    }

    if (is_string($_GET[$nombre])) {
        return trim($_GET[$nombre]);
    }

    return $_GET[$nombre];
}


/**
 *
 * @param string $horaInicio
 * @return string
 */
function FormatearHoraInicioFuncionController($horaInicio)
{
    $horaInicio = trim((string) $horaInicio);

    if ($horaInicio === '') {
        throw new InvalidArgumentException(
            'Debe ingresar la fecha y hora de inicio.'
        );
    }

    $horaInicio = str_replace('T', ' ', $horaInicio);

    if (strlen($horaInicio) === 16) {
        $horaInicio .= ':00';
    }

    $fecha = DateTime::createFromFormat(
        'Y-m-d H:i:s',
        $horaInicio
    );

    $errores = DateTime::getLastErrors();

    if (
        !$fecha ||
        (
            is_array($errores) &&
            (
                $errores['warning_count'] > 0 ||
                $errores['error_count'] > 0
            )
        )
    ) {
        throw new InvalidArgumentException(
            'La fecha y hora ingresadas no tienen un formato válido.'
        );
    }

    return $fecha->format('Y-m-d H:i:s');
}


/**
 * 
 *
 * @param mixed $valor
 * @param string $nombreCampo
 * @return int
 */
function ValidarIdFuncionController(
    $valor,
    $nombreCampo
) {
    if (
        $valor === null ||
        $valor === '' ||
        filter_var($valor, FILTER_VALIDATE_INT) === false
    ) {
        throw new InvalidArgumentException(
            'El campo ' . $nombreCampo . ' no es válido.'
        );
    }

    $id = (int) $valor;

    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El campo ' . $nombreCampo . ' debe ser mayor que cero.'
        );
    }

    return $id;
}


/**
 * 
 *
 * @param mixed $precio
 * @return float
 */
function ValidarPrecioFuncionController($precio)
{
    if (
        $precio === null ||
        $precio === '' ||
        !is_numeric($precio)
    ) {
        throw new InvalidArgumentException(
            'Debe ingresar un precio válido.'
        );
    }

    $precio = (float) $precio;

    if ($precio <= 0) {
        throw new InvalidArgumentException(
            'El precio debe ser mayor que cero.'
        );
    }

    return $precio;
}


/**
 * 
 *
 * @param mixed $valor
 * @param string $nombreCampo
 * @param int $longitudMaxima
 * @return string
 */
function ValidarTextoFuncionController(
    $valor,
    $nombreCampo,
    $longitudMaxima = 45
) {
    $valor = trim((string) $valor);

    if ($valor === '') {
        throw new InvalidArgumentException(
            'Debe ingresar el campo ' . $nombreCampo . '.'
        );
    }

    if (mb_strlen($valor) > $longitudMaxima) {
        throw new InvalidArgumentException(
            'El campo ' . $nombreCampo
            . ' no puede superar los '
            . $longitudMaxima
            . ' caracteres.'
        );
    }

    return $valor;
}


/**
 * 
 *
 * @return array
 */
function ConsultarFuncionesController()
{
    return ConsultarFuncionesModel();
}


/**
 * 
 *
 * @param mixed $idFuncion
 * @return array|null
 */
function ConsultarFuncionPorIdController($idFuncion)
{
    $idFuncion = ValidarIdFuncionController(
        $idFuncion,
        'identificador de la función'
    );

    return ConsultarFuncionPorIdModel($idFuncion);
}


/**
 * 
 *
 * @return array
 */
function ConsultarPeliculasActivasController()
{
    return ConsultarPeliculasActivasFuncionModel();
}


/**
 * 
 *
 * @return array
 */
function ConsultarCinesController()
{
    return ConsultarCinesFuncionModel();
}


/**
 * 
 *
 * @param mixed $idCine
 * @return array
 */
function ConsultarSalasPorCineController($idCine)
{
    $idCine = ValidarIdFuncionController(
        $idCine,
        'identificador del cine'
    );

    return ConsultarSalasPorCineFuncionModel($idCine);
}


/**
 * 
 *
 * @param array $datos
 * @return array
 */
function RegistrarFuncionController($datos)
{
    $idPelicula = ValidarIdFuncionController(
        $datos['idPelicula'] ?? null,
        'película'
    );

    $idSala = ValidarIdFuncionController(
        $datos['idSala'] ?? null,
        'sala'
    );

    $horaInicio = FormatearHoraInicioFuncionController(
        $datos['horaInicio'] ?? ''
    );

    $precio = ValidarPrecioFuncionController(
        $datos['precio'] ?? null
    );

    $idioma = ValidarTextoFuncionController(
        $datos['idioma'] ?? '',
        'idioma'
    );

    $formato = ValidarTextoFuncionController(
        $datos['formato'] ?? '',
        'formato'
    );

    return RegistrarFuncionModel(
        $idPelicula,
        $idSala,
        $horaInicio,
        $precio,
        $idioma,
        $formato
    );
}


/**
 * 
 *
 * @param array $datos
 * @return array
 */
function ActualizarFuncionController($datos)
{
    $idFuncion = ValidarIdFuncionController(
        $datos['idFuncion'] ?? null,
        'identificador de la función'
    );

    $idPelicula = ValidarIdFuncionController(
        $datos['idPelicula'] ?? null,
        'película'
    );

    $idSala = ValidarIdFuncionController(
        $datos['idSala'] ?? null,
        'sala'
    );

    $horaInicio = FormatearHoraInicioFuncionController(
        $datos['horaInicio'] ?? ''
    );

    $precio = ValidarPrecioFuncionController(
        $datos['precio'] ?? null
    );

    $idioma = ValidarTextoFuncionController(
        $datos['idioma'] ?? '',
        'idioma'
    );

    $formato = ValidarTextoFuncionController(
        $datos['formato'] ?? '',
        'formato'
    );

    return ActualizarFuncionModel(
        $idFuncion,
        $idPelicula,
        $idSala,
        $horaInicio,
        $precio,
        $idioma,
        $formato
    );
}


/**
 * 
 *
 * @param mixed $idFuncion
 * @return array
 */
function EliminarFuncionController($idFuncion)
{
    $idFuncion = ValidarIdFuncionController(
        $idFuncion,
        'identificador de la función'
    );

    return EliminarFuncionModel($idFuncion);
}


/**
 *
 * @return void
 */
function EjecutarAccionFuncionController()
{
    $accion = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $accion = ObtenerPostFuncionController('accion', '');
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $accion = ObtenerGetFuncionController('accion', '');
    }

    if ($accion === '') {
        ResponderJsonFuncionController(
            false,
            'No se indicó ninguna acción.',
            null,
            400
        );
    }

    try {
        switch ($accion) {
            case 'consultarFunciones':

                $funciones = ConsultarFuncionesController();

                ResponderJsonFuncionController(
                    true,
                    'Funciones consultadas correctamente.',
                    $funciones
                );

                break;

            case 'consultarFuncionPorId':

                $idFuncion = $_POST['idFuncion']
                    ?? $_GET['idFuncion']
                    ?? null;

                $funcion = ConsultarFuncionPorIdController(
                    $idFuncion
                );

                if (!$funcion) {
                    ResponderJsonFuncionController(
                        false,
                        'La función solicitada no existe.',
                        null,
                        404
                    );
                }

                ResponderJsonFuncionController(
                    true,
                    'Función consultada correctamente.',
                    $funcion
                );

                break;

            case 'consultarPeliculas':

                $peliculas = ConsultarPeliculasActivasController();

                ResponderJsonFuncionController(
                    true,
                    'Películas consultadas correctamente.',
                    $peliculas
                );

                break;

            case 'consultarCines':

                $cines = ConsultarCinesController();

                ResponderJsonFuncionController(
                    true,
                    'Cines consultados correctamente.',
                    $cines
                );

                break;

            case 'consultarSalas':

                $idCine = $_POST['idCine']
                    ?? $_GET['idCine']
                    ?? null;

                $salas = ConsultarSalasPorCineController(
                    $idCine
                );

                ResponderJsonFuncionController(
                    true,
                    'Salas consultadas correctamente.',
                    $salas
                );

                break;

            case 'registrarFuncion':

                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    ResponderJsonFuncionController(
                        false,
                        'El registro debe realizarse mediante una solicitud POST.',
                        null,
                        405
                    );
                }

                $respuesta = RegistrarFuncionController($_POST);

                ResponderJsonFuncionController(
                    true,
                    $respuesta['Mensaje']
                        ?? 'Función registrada correctamente.',
                    $respuesta
                );

                break;

            case 'actualizarFuncion':

                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    ResponderJsonFuncionController(
                        false,
                        'La actualización debe realizarse mediante una solicitud POST.',
                        null,
                        405
                    );
                }

                $respuesta = ActualizarFuncionController($_POST);

                ResponderJsonFuncionController(
                    true,
                    $respuesta['Mensaje']
                        ?? 'Función actualizada correctamente.',
                    $respuesta
                );

                break;

            case 'eliminarFuncion':

                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    ResponderJsonFuncionController(
                        false,
                        'La eliminación debe realizarse mediante una solicitud POST.',
                        null,
                        405
                    );
                }

                $idFuncion = ObtenerPostFuncionController(
                    'idFuncion'
                );

                $respuesta = EliminarFuncionController(
                    $idFuncion
                );

                ResponderJsonFuncionController(
                    true,
                    $respuesta['Mensaje']
                        ?? 'Función eliminada correctamente.',
                    $respuesta
                );

                break;

            default:

                ResponderJsonFuncionController(
                    false,
                    'La acción solicitada no es válida.',
                    null,
                    400
                );
        }
    } catch (InvalidArgumentException $ex) {
        ResponderJsonFuncionController(
            false,
            $ex->getMessage(),
            null,
            400
        );
    } catch (mysqli_sql_exception $ex) {
        ResponderJsonFuncionController(
            false,
            ObtenerMensajeMysqlFuncionController($ex),
            null,
            400
        );
    } catch (Throwable $ex) {
        ResponderJsonFuncionController(
            false,
            'Ocurrió un error al procesar la solicitud: '
                . $ex->getMessage(),
            null,
            500
        );
    }
}


/**
 *
 * @param mysqli_sql_exception $ex
 * @return string
 */
function ObtenerMensajeMysqlFuncionController($ex)
{
    $mensaje = trim($ex->getMessage());

    if ($mensaje === '') {
        return 'Ocurrió un error al realizar la operación en la base de datos.';
    }

    return $mensaje;
}



$archivoActual = realpath(__FILE__);
$archivoSolicitado = isset($_SERVER['SCRIPT_FILENAME'])
    ? realpath($_SERVER['SCRIPT_FILENAME'])
    : null;

if (
    $archivoActual !== false &&
    $archivoSolicitado !== false &&
    $archivoActual === $archivoSolicitado
) {
    EjecutarAccionFuncionController();
}
