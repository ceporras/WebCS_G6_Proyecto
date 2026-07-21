<?php

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Model/utilModel.php';


function LimpiarResultadosCineModel($conn)
{
    while ($conn->more_results()) {
        $conn->next_result();

        if ($resultado = $conn->store_result()) {
            $resultado->free();
        }
    }
}

/**
 * Consulta todos los cines
 */
function ConsultarCinesModel()
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

        LimpiarResultadosCineModel($conn);

        return $cines;
    } finally {
        CloseDB($conn);
    }
}

/**
 * Consulta un cine mediante su ID
 */
function ConsultarCinePorIdModel($idCine)
{
    $conn = OpenDB();
    $cine = null;

    try {
        $stmt = $conn->prepare(
            "CALL spGetCineById(?)"
        );

        $stmt->bind_param(
            "i",
            $idCine
        );

        $stmt->execute();

        $resultado = $stmt->get_result();
        $cine = $resultado->fetch_assoc();

        $resultado->free();
        $stmt->close();

        LimpiarResultadosCineModel($conn);

        return $cine;
    } finally {
        CloseDB($conn);
    }
}

/**
 * Registra un cine.
 */
function RegistrarCineModel(
    $nombre,
    $direccion,
    $ciudad,
    $telefono,
    $correo
) {
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare(
            "CALL spAddCine(?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssss",
            $nombre,
            $direccion,
            $ciudad,
            $telefono,
            $correo
        );

        $resultado = $stmt->execute();
        $stmt->close();

        LimpiarResultadosCineModel($conn);

        return $resultado;
    } finally {
        CloseDB($conn);
    }
}

/**
 * Actualiza un cine.
 */
function ActualizarCineModel(
    $idCine,
    $nombre,
    $direccion,
    $ciudad,
    $telefono,
    $correo
) {
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare(
            "CALL spUpdateCine(?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "isssss",
            $idCine,
            $nombre,
            $direccion,
            $ciudad,
            $telefono,
            $correo
        );

        $resultado = $stmt->execute();
        $stmt->close();

        LimpiarResultadosCineModel($conn);

        return $resultado;
    } finally {
        CloseDB($conn);
    }
}

/**
 * Elimina un cine.
 */
function EliminarCineModel($idCine)
{
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare(
            "CALL spDeleteCine(?)"
        );

        $stmt->bind_param(
            "i",
            $idCine
        );

        $resultado = $stmt->execute();
        $stmt->close();

        LimpiarResultadosCineModel($conn);

        return $resultado;
    } finally {
        CloseDB($conn);
    }
}