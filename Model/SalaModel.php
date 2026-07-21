<?php

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Model/utilModel.php';

/** Limpiar resultados
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

/**Obtiene salas y el nombre del cine.
 */
function ConsultarSalasModel()
{
    $conn = OpenDB();
    $salas = [];

    try {
        $stmt = $conn->prepare("CALL spGetSalas()");

        if (!$stmt) {
            throw new Exception($conn->error);
        }

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
        $conn->close();
    }
}

/**Obtiene una sala mediante su ID.
 */
function ConsultarSalaPorIdModel($idSala)
{
    $conn = OpenDB();
    $sala = null;

    try {
        $stmt = $conn->prepare("CALL spGetSalaById(?)");

        if (!$stmt) {
            throw new Exception($conn->error);
        }

        $stmt->bind_param("i", $idSala);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $sala = $resultado->fetch_assoc();

        $resultado->free();
        $stmt->close();

        LimpiarResultadosSalaModel($conn);

        return $sala;
    } finally {
        $conn->close();
    }
}

/** Obtiene los cines para llenar el selector.
 */
function ConsultarCinesParaSalaModel()
{
    $conn = OpenDB();
    $cines = [];

    try {
        $sql = "
            SELECT
                ID_Cine,
                Nombre
            FROM cine_tb
            ORDER BY Nombre
        ";

        $resultado = $conn->query($sql);

        if (!$resultado) {
            throw new Exception($conn->error);
        }

        while ($fila = $resultado->fetch_assoc()) {
            $cines[] = $fila;
        }

        $resultado->free();

        return $cines;
    } finally {
        $conn->close();
    }
}

/*Registra una sala.
 */
function RegistrarSalaModel(
    $idCine,
    $nombre,
    $capacidad,
    $tipoPantalla
) {
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare("CALL spAddSala(?, ?, ?, ?)");

        if (!$stmt) {
            throw new Exception($conn->error);
        }

        $stmt->bind_param(
            "isis",
            $idCine,
            $nombre,
            $capacidad,
            $tipoPantalla
        );

        $resultado = $stmt->execute();
        $stmt->close();

        LimpiarResultadosSalaModel($conn);

        return $resultado;
    } finally {
        $conn->close();
    }
}

/** Actualiza una sala.
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

        if (!$stmt) {
            throw new Exception($conn->error);
        }

        $stmt->bind_param(
            "iisis",
            $idSala,
            $idCine,
            $nombre,
            $capacidad,
            $tipoPantalla
        );

        $resultado = $stmt->execute();
        $stmt->close();

        LimpiarResultadosSalaModel($conn);

        return $resultado;
    } finally {
        $conn->close();
    }
}

/** Elimina una sala.
 */
function EliminarSalaModel($idSala)
{
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare("CALL spDeleteSala(?)");

        if (!$stmt) {
            throw new Exception($conn->error);
        }

        $stmt->bind_param("i", $idSala);

        $resultado = $stmt->execute();
        $stmt->close();

        LimpiarResultadosSalaModel($conn);

        return $resultado;
    } finally {
        $conn->close();
    }
}