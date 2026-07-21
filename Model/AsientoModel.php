<?php

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Model/utilModel.php';

function LimpiarResultadosAsientoModel($conn)
{
    while ($conn->more_results()) {
        $conn->next_result();

        if ($resultado = $conn->store_result()) {
            $resultado->free();
        }
    }
}

function ConsultarAsientosModel()
{
    $conn = OpenDB();
    $asientos = [];

    try {
        $stmt = $conn->prepare("CALL spGetAsientos()");

        if (!$stmt) {
            throw new Exception($conn->error);
        }

        $stmt->execute();
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $asientos[] = $fila;
        }

        $resultado->free();
        $stmt->close();

        LimpiarResultadosAsientoModel($conn);

        return $asientos;
    } finally {
        $conn->close();
    }
}

function ConsultarAsientoPorIdModel($idAsiento)
{
    $conn = OpenDB();
    $asiento = null;

    try {
        $stmt = $conn->prepare(
            "CALL spGetAsientoById(?)"
        );

        if (!$stmt) {
            throw new Exception($conn->error);
        }

        $stmt->bind_param("i", $idAsiento);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $asiento = $resultado->fetch_assoc();

        $resultado->free();
        $stmt->close();

        LimpiarResultadosAsientoModel($conn);

        return $asiento;
    } finally {
        $conn->close();
    }
}

function ConsultarSalasParaAsientoModel()
{
    $conn = OpenDB();
    $salas = [];

    try {
        $sql = "
            SELECT
                s.ID_Sala,
                s.Nombre AS NombreSala,
                c.Nombre AS NombreCine
            FROM sala_tb AS s
            INNER JOIN cine_tb AS c
                ON s.ID_Cine = c.ID_Cine
            ORDER BY c.Nombre, s.Nombre
        ";

        $resultado = $conn->query($sql);

        if (!$resultado) {
            throw new Exception($conn->error);
        }

        while ($fila = $resultado->fetch_assoc()) {
            $salas[] = $fila;
        }

        $resultado->free();

        return $salas;
    } finally {
        $conn->close();
    }
}

function RegistrarAsientoModel(
    $idSala,
    $fila,
    $numero,
    $tipoAsiento
) {
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare(
            "CALL spAddAsiento(?, ?, ?, ?)"
        );

        if (!$stmt) {
            throw new Exception($conn->error);
        }

        $stmt->bind_param(
            "isis",
            $idSala,
            $fila,
            $numero,
            $tipoAsiento
        );

        $resultado = $stmt->execute();
        $stmt->close();

        LimpiarResultadosAsientoModel($conn);

        return $resultado;
    } finally {
        $conn->close();
    }
}

function ActualizarAsientoModel(
    $idAsiento,
    $idSala,
    $fila,
    $numero,
    $tipoAsiento
) {
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare(
            "CALL spUpdateAsiento(?, ?, ?, ?, ?)"
        );

        if (!$stmt) {
            throw new Exception($conn->error);
        }

        $stmt->bind_param(
            "iisis",
            $idAsiento,
            $idSala,
            $fila,
            $numero,
            $tipoAsiento
        );

        $resultado = $stmt->execute();
        $stmt->close();

        LimpiarResultadosAsientoModel($conn);

        return $resultado;
    } finally {
        $conn->close();
    }
}

function EliminarAsientoModel($idAsiento)
{
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare(
            "CALL spDeleteAsiento(?)"
        );

        if (!$stmt) {
            throw new Exception($conn->error);
        }

        $stmt->bind_param("i", $idAsiento);

        $resultado = $stmt->execute();
        $stmt->close();

        LimpiarResultadosAsientoModel($conn);

        return $resultado;
    } finally {
        $conn->close();
    }
}