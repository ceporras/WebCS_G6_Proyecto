<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/Model/UtilModel.php';

function getFunciones($ID_Pelicula)
{
    $conn = OpenDB();
    $sql = "
        SELECT
            f.ID_Funcion,
            f.HoraInicio,
            f.Idioma,
            f.Formato,
            s.Nombre AS Sala
        FROM funcion_tb f
        INNER JOIN sala_tb s
            ON s.ID_Sala = f.ID_Sala
        WHERE f.ID_Pelicula = ?
        ORDER BY f.Idioma, f.HoraInicio
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ID_Pelicula);
    $stmt->execute();

    $result = $stmt->get_result();

    CloseDB($conn);
    return $result;
}


function getPelicula($ID_Pelicula)
{
    try {
        $conn = OpenDB();
        $sql = "
        SELECT *
        FROM pelicula_tb 
        WHERE ID_Pelicula = ? ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $ID_Pelicula);
        $stmt->execute();

        $result = $stmt->get_result();

        CloseDB($conn);
        return $result;
    } catch (Exception $e) {
        echo $e;
    }
}
