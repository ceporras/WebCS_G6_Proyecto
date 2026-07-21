<?php

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Model/utilModel.php';


/**
 *
 * @return array
 * @throws Throwable
 */
function ConsultarFuncionesModel()
{
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare(
            "CALL sp_ConsultarFunciones()"
        );

        $stmt->execute();

        $resultado = $stmt->get_result();
        $funciones = [];

        while ($fila = $resultado->fetch_assoc()) {
            $funciones[] = $fila;
        }

        $resultado->free();
        $stmt->close();

        LimpiarResultadosFuncionModel($conn);

        return $funciones;

    } finally {
        CloseDB($conn);
    }
}


/**
 *
 * @param int $idFuncion
 * @return array|null
 * @throws InvalidArgumentException
 * @throws Throwable
 */
function ConsultarFuncionPorIdModel($idFuncion)
{
    $idFuncion = (int) $idFuncion;

    if ($idFuncion <= 0) {
        throw new InvalidArgumentException(
            'El identificador de la función no es válido.'
        );
    }

    $conn = OpenDB();

    try {
        $stmt = $conn->prepare(
            "CALL sp_ConsultarFuncionPorId(?)"
        );

        $stmt->bind_param(
            "i",
            $idFuncion
        );

        $stmt->execute();

        $resultado = $stmt->get_result();
        $funcion = $resultado->fetch_assoc();

        $resultado->free();
        $stmt->close();

        LimpiarResultadosFuncionModel($conn);

        return $funcion ?: null;

    } finally {
        CloseDB($conn);
    }
}


/**
 *
 * @return array
 * @throws Throwable
 */
function ConsultarPeliculasActivasFuncionModel()
{
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare(
            "CALL sp_ConsultarPeliculasActivas()"
        );

        $stmt->execute();

        $resultado = $stmt->get_result();
        $peliculas = [];

        while ($fila = $resultado->fetch_assoc()) {
            $peliculas[] = $fila;
        }

        $resultado->free();
        $stmt->close();

        LimpiarResultadosFuncionModel($conn);

        return $peliculas;

    } finally {
        CloseDB($conn);
    }
}


/**
 *
 * @return array
 * @throws Throwable
 */
function ConsultarCinesFuncionModel()
{
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare(
            "CALL sp_ConsultarCines()"
        );

        $stmt->execute();

        $resultado = $stmt->get_result();
        $cines = [];

        while ($fila = $resultado->fetch_assoc()) {
            $cines[] = $fila;
        }

        $resultado->free();
        $stmt->close();

        LimpiarResultadosFuncionModel($conn);

        return $cines;

    } finally {
        CloseDB($conn);
    }
}


/**
 *
 * @param int $idCine
 * @return array
 * @throws InvalidArgumentException
 * @throws Throwable
 */
function ConsultarSalasPorCineFuncionModel($idCine)
{
    $idCine = (int) $idCine;

    if ($idCine <= 0) {
        throw new InvalidArgumentException(
            'El identificador del cine no es válido.'
        );
    }

    $conn = OpenDB();

    try {
        $stmt = $conn->prepare(
            "CALL sp_ConsultarSalasPorCine(?)"
        );

        $stmt->bind_param(
            "i",
            $idCine
        );

        $stmt->execute();

        $resultado = $stmt->get_result();
        $salas = [];

        while ($fila = $resultado->fetch_assoc()) {
            $salas[] = $fila;
        }

        $resultado->free();
        $stmt->close();

        LimpiarResultadosFuncionModel($conn);

        return $salas;

    } finally {
        CloseDB($conn);
    }
}


/**
 *
 * @param int $idPelicula
 * @param int $idSala
 * @param string $horaInicio Formato: YYYY-MM-DD HH:MM:SS
 * @param float $precio
 * @param string $idioma
 * @param string $formato
 * @return array
 * @throws InvalidArgumentException
 * @throws Throwable
 */
function RegistrarFuncionModel(
    $idPelicula,
    $idSala,
    $horaInicio,
    $precio,
    $idioma,
    $formato
) {
    $idPelicula = (int) $idPelicula;
    $idSala = (int) $idSala;
    $precio = (float) $precio;
    $horaInicio = trim((string) $horaInicio);
    $idioma = trim((string) $idioma);
    $formato = trim((string) $formato);

    ValidarDatosFuncionModel(
        $idPelicula,
        $idSala,
        $horaInicio,
        $precio,
        $idioma,
        $formato
    );

    $conn = OpenDB();

    try {
        $stmt = $conn->prepare(
            "CALL sp_RegistrarFuncion(?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "iisdss",
            $idPelicula,
            $idSala,
            $horaInicio,
            $precio,
            $idioma,
            $formato
        );

        $stmt->execute();

        $resultado = $stmt->get_result();
        $respuesta = $resultado->fetch_assoc();

        $resultado->free();
        $stmt->close();

        LimpiarResultadosFuncionModel($conn);

        if (!$respuesta || !isset($respuesta['ID_Funcion'])) {
            throw new RuntimeException(
                'No se pudo obtener el identificador de la función registrada.'
            );
        }

        return $respuesta;

    } finally {
        CloseDB($conn);
    }
}


/**
 *
 * @param int $idFuncion
 * @param int $idPelicula
 * @param int $idSala
 * @param string $horaInicio
 * @param float $precio
 * @param string $idioma
 * @param string $formato
 * @return array
 * @throws InvalidArgumentException
 * @throws Throwable
 */
function ActualizarFuncionModel(
    $idFuncion,
    $idPelicula,
    $idSala,
    $horaInicio,
    $precio,
    $idioma,
    $formato
) {
    $idFuncion = (int) $idFuncion;
    $idPelicula = (int) $idPelicula;
    $idSala = (int) $idSala;
    $precio = (float) $precio;
    $horaInicio = trim((string) $horaInicio);
    $idioma = trim((string) $idioma);
    $formato = trim((string) $formato);

    if ($idFuncion <= 0) {
        throw new InvalidArgumentException(
            'El identificador de la función no es válido.'
        );
    }

    ValidarDatosFuncionModel(
        $idPelicula,
        $idSala,
        $horaInicio,
        $precio,
        $idioma,
        $formato
    );

    $conn = OpenDB();

    try {
        $stmt = $conn->prepare(
            "CALL sp_ActualizarFuncion(?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "iiisdss",
            $idFuncion,
            $idPelicula,
            $idSala,
            $horaInicio,
            $precio,
            $idioma,
            $formato
        );

        $stmt->execute();

        $resultado = $stmt->get_result();
        $respuesta = $resultado->fetch_assoc();

        $resultado->free();
        $stmt->close();

        LimpiarResultadosFuncionModel($conn);

        if (!$respuesta) {
            throw new RuntimeException(
                'No se recibió una respuesta al actualizar la función.'
            );
        }

        return $respuesta;

    } finally {
        CloseDB($conn);
    }
}


/**
 *
 * @param int $idFuncion
 * @return array
 * @throws InvalidArgumentException
 * @throws Throwable
 */
function EliminarFuncionModel($idFuncion)
{
    $idFuncion = (int) $idFuncion;

    if ($idFuncion <= 0) {
        throw new InvalidArgumentException(
            'El identificador de la función no es válido.'
        );
    }

    $conn = OpenDB();

    try {
        $stmt = $conn->prepare(
            "CALL sp_EliminarFuncion(?)"
        );

        $stmt->bind_param(
            "i",
            $idFuncion
        );

        $stmt->execute();

        $resultado = $stmt->get_result();
        $respuesta = $resultado->fetch_assoc();

        $resultado->free();
        $stmt->close();

        LimpiarResultadosFuncionModel($conn);

        if (!$respuesta) {
            throw new RuntimeException(
                'No se recibió una respuesta al eliminar la función.'
            );
        }

        return $respuesta;

    } finally {
        CloseDB($conn);
    }
}


/**
 *
 * @param int $idPelicula
 * @param int $idSala
 * @param string $horaInicio
 * @param float $precio
 * @param string $idioma
 * @param string $formato
 * @return void
 * @throws InvalidArgumentException
 */
function ValidarDatosFuncionModel(
    $idPelicula,
    $idSala,
    $horaInicio,
    $precio,
    $idioma,
    $formato
) {
    if ($idPelicula <= 0) {
        throw new InvalidArgumentException(
            'Debe seleccionar una película válida.'
        );
    }

    if ($idSala <= 0) {
        throw new InvalidArgumentException(
            'Debe seleccionar una sala válida.'
        );
    }

    if ($horaInicio === '') {
        throw new InvalidArgumentException(
            'Debe ingresar la fecha y hora de inicio.'
        );
    }

    $fechaInicio = DateTime::createFromFormat(
        'Y-m-d H:i:s',
        $horaInicio
    );

    $erroresFecha = DateTime::getLastErrors();

    if (
        !$fechaInicio ||
        (
            is_array($erroresFecha) &&
            (
                $erroresFecha['warning_count'] > 0 ||
                $erroresFecha['error_count'] > 0
            )
        )
    ) {
        throw new InvalidArgumentException(
            'La fecha y hora deben utilizar el formato YYYY-MM-DD HH:MM:SS.'
        );
    }

    if ($precio <= 0) {
        throw new InvalidArgumentException(
            'El precio debe ser mayor que cero.'
        );
    }

    if ($idioma === '') {
        throw new InvalidArgumentException(
            'Debe ingresar el idioma de la función.'
        );
    }

    if (mb_strlen($idioma) > 45) {
        throw new InvalidArgumentException(
            'El idioma no puede superar los 45 caracteres.'
        );
    }

    if ($formato === '') {
        throw new InvalidArgumentException(
            'Debe ingresar el formato de la función.'
        );
    }

    if (mb_strlen($formato) > 45) {
        throw new InvalidArgumentException(
            'El formato no puede superar los 45 caracteres.'
        );
    }
}


/**
 *
 * @param mysqli $conn
 * @return void
 */
function LimpiarResultadosFuncionModel($conn)
{
    while ($conn->more_results()) {
        $conn->next_result();

        $resultadoPendiente = $conn->store_result();

        if ($resultadoPendiente instanceof mysqli_result) {
            $resultadoPendiente->free();
        }
    }
}