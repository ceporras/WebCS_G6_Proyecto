<?php

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Model/utilModel.php';

function ConsultarCinesModel()
{
    $conn = OpenDB();

    try {
        $sql = "
            SELECT
                ID_Cine,
                Nombre,
                Direccion,
                Ciudad,
                Telefono,
                Correo
            FROM cine_tb
            ORDER BY Nombre
        ";

        $resultado = $conn->query($sql);
        $cines = [];

        while ($fila = $resultado->fetch_assoc()) {
            $cines[] = $fila;
        }

        $resultado->free();

        return $cines;
    } finally {
        CloseDB($conn);
    }
}

function ConsultarCinePorIdModel($idCine)
{
    $conn = OpenDB();

    try {
        $sql = "
            SELECT
                ID_Cine,
                Nombre,
                Direccion,
                Ciudad,
                Telefono,
                Correo
            FROM cine_tb
            WHERE ID_Cine = ?
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idCine);
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

function RegistrarCineModel(
    $nombre,
    $direccion,
    $ciudad,
    $telefono,
    $correo
) {
    $conn = OpenDB();

    try {
        $sql = "
            INSERT INTO cine_tb
                (Nombre, Direccion, Ciudad, Telefono, Correo)
            VALUES
                (?, ?, ?, ?, ?)
        ";

        $stmt = $conn->prepare($sql);

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
        $sql = "
            UPDATE cine_tb
            SET
                Nombre = ?,
                Direccion = ?,
                Ciudad = ?,
                Telefono = ?,
                Correo = ?
            WHERE ID_Cine = ?
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sssssi",
            $nombre,
            $direccion,
            $ciudad,
            $telefono,
            $correo,
            $idCine
        );

        $stmt->execute();
        $stmt->close();

        return true;
    } finally {
        CloseDB($conn);
    }
}

function EliminarCineModel($idCine)
{
    $conn = OpenDB();

    try {
        $sql = "
            DELETE FROM cine_tb
            WHERE ID_Cine = ?
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idCine);
        $stmt->execute();
        $stmt->close();

        return true;
    } finally {
        CloseDB($conn);
    }
}