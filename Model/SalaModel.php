<?php

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Model/utilModel.php';

/**
 * Limpia resultados pendientes después de ejecutar
 * procedimientos almacenados con MySQLi.
 */
function LimpiarResultadosSalaModel($conn)
{
    while ($conn->more_results()) {
        $conn->next_result();

        if ($resultado = $conn->store_result()) {
            $resultado->free();
        }
    }
}

/**
 * Registra una sala.
 */
function RegistrarSalaModel(
    $idCine,
    $nombre,
    $capacidad,
    $tipoPantalla
) {
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare(
            "CALL spAddSala(?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "isis",
            $idCine,
            $nombre,
            $capacidad,
            $tipoPantalla
        );

        $stmt->execute();
        $stmt->close();

        LimpiarResultadosSalaModel($conn);

        return true;

    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "RegistrarSalaModel", $e);
        return false;

    } finally {
        CloseDB($conn);
    }
}

/**
 * Consulta todas las salas.
 */
function ConsultarSalasModel()
{
    $conn = OpenDB();
    $salas = [];

    try {
        $stmt = $conn->prepare(
            "CALL spGetSalas()"
        );

        $stmt->execute();
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $salas[] = $fila;
        }

        $resultado->free();
        $stmt->close();

        LimpiarResultadosSalaModel($conn);

        return $salas;

    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "ConsultarSalasModel", $e);
        return false;

    } finally {
        CloseDB($conn);
    }
}

/**
 * Consulta una sala por su ID.
 */
function ConsultarSalaPorIdModel($idSala)
{
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare(
            "CALL spGetSalaById(?)"
        );

        $stmt->bind_param(
            "i",
            $idSala
        );

        $stmt->execute();
        $resultado = $stmt->get_result();

        $sala = $resultado->fetch_assoc();

        $resultado->free();
        $stmt->close();

        LimpiarResultadosSalaModel($conn);

        return $sala ?: null;

    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "ConsultarSalaPorIdModel", $e);
        return false;

    } finally {
        CloseDB($conn);
    }
}

/**
 * Actualiza una sala.
 */
function ActualizarSalaModel(
    $idSala,
    $idCine,
    $nombre,
    $capacidad,
    $tipoPantalla
) {
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare(
            "CALL spUpdateSala(?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "iisis",
            $idSala,
            $idCine,
            $nombre,
            $capacidad,
            $tipoPantalla
        );

        $stmt->execute();
        $stmt->close();

        LimpiarResultadosSalaModel($conn);

        return true;

    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "ActualizarSalaModel", $e);
        return false;

    } finally {
        CloseDB($conn);
    }
}

/**
 * Elimina una sala.
 */
function EliminarSalaModel($idSala)
{
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare(
            "CALL spDeleteSala(?)"
        );

        $stmt->bind_param(
            "i",
            $idSala
        );

        $stmt->execute();
        $stmt->close();

        LimpiarResultadosSalaModel($conn);

        return true;

    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "EliminarSalaModel", $e);
        return false;

    } finally {
        CloseDB($conn);
    }
}

/**
 * Obtiene los cines para llenar el selector.
 */
function ConsultarCinesParaSalaModel()
{
    $conn = OpenDB();
    $cines = [];

    try {
        $stmt = $conn->prepare(
            "CALL spGetCines()"
        );

        $stmt->execute();
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $cines[] = $fila;
        }

        $resultado->free();
        $stmt->close();

        LimpiarResultadosSalaModel($conn);

        return $cines;

    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "ConsultarCinesParaSalaModel", $e);
        return false;
        
    } finally {
        CloseDB($conn);
    }
}