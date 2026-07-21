<?php

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Model/utilModel.php';

/* Consultar cines */

function ConsultarCinesModel()
{
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare(
            "CALL spGetCines()"
        );

        $stmt->execute();

        $resultado = $stmt->get_result();
        $cines = [];

        while ($fila = $resultado->fetch_assoc()) {
            $cines[] = $fila;
        }

        $resultado->free();
        $stmt->close();

        return $cines;
    } finally {
        CloseDB($conn);
    }
}

/* Consultar cine por ID */

function ConsultarCinePorIdModel($idCine)
{
    $conn = OpenDB();

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

        return $cine;
    } finally {
        CloseDB($conn);
    }
}

/* Registrar cine */

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

        $stmt->execute();
        $stmt->close();

        return true;
    } finally {
        CloseDB($conn);
    }
}

/* Actualizar cine */

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

        $stmt->execute();
        $stmt->close();

        return true;
    } finally {
        CloseDB($conn);
    }
}

/* Eliminar cine */

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

        $stmt->execute();
        $stmt->close();

        return true;
    } finally {
        CloseDB($conn);
    }
}