<?php

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Model/utilModel.php';

function LimpiarResultadosSalaModel($conn)
{
    while ($conn->more_results()) {
        $conn->next_result();

        if ($resultado = $conn->store_result()) {
            $resultado->free();
        }
    }
}

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
    } finally {
        CloseDB($conn);
    }
}