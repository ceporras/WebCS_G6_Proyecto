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

    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "ConsultarAsientosModel", $e);
        return false;

    } finally {
        CloseDB($conn);
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

    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "ConsultarAsientoPorIdModel", $e);
        return false;

    } finally {
        CloseDB($conn);
    }
}

function ConsultarSalasParaAsientoModel()
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

        LimpiarResultadosAsientoModel($conn);

        return $salas;

    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "ConsultarSalasParaAsientoModel", $e);
        return false;

    } finally {
        CloseDB($conn);
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

    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "RegistrarAsientoModel", $e);
        return false;

    } finally {
        CloseDB($conn);
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

    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "ActualizarAsientoModel", $e);
        return false;

    } finally {
        CloseDB($conn);
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

    } catch (Exception $e) {
        addLog(timestamp(), "ERROR", "EliminarAsientoModel", $e);
        return false;

    } finally {
        CloseDB($conn);
    }
}
