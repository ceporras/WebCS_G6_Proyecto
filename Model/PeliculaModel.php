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

    $conn = OpenDB();
    $sql = "CALL spGetPelicula('$ID_Pelicula')";
    $result = mysqli_query($conn, $sql);
    CloseDB($conn);
    return $result;
}


/* CRUD GÉNEROS */

function ConsultarGenerosModel()
{
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare("CALL spGetGeneros()");
        $stmt->execute();

        $resultado = $stmt->get_result();
        $generos = [];

        while ($fila = $resultado->fetch_assoc()) {
            $generos[] = $fila;
        }

        $resultado->free();
        $stmt->close();

        return $generos;
    } finally {
        CloseDB($conn);
    }
}



function ConsultarGeneroPorIdModel($idGenero)
{
    $conn = OpenDB();

    try {

        $stmt = $conn->prepare("CALL spGetGeneroById(?)");
        $stmt->bind_param("i", $idGenero);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $genero = $resultado->fetch_assoc();

        $resultado->free();
        $stmt->close();

        return $genero;
    } finally {
        CloseDB($conn);
    }
}

function RegistrarGeneroModel($nombre)
{
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare("CALL spAddGenero(?)");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $stmt->close();

        return true;
    } finally {
        CloseDB($conn);
    }
}

function ActualizarGeneroModel($idGenero, $nombre)
{
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare("CALL spUpdateGenero(?, ?)");
        $stmt->bind_param("is", $idGenero, $nombre);
        $stmt->execute();
        $stmt->close();

        return true;
    } finally {
        CloseDB($conn);
    }
}

function EliminarGeneroModel($idGenero)
{
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare("CALL spDeleteGenero(?)");
        $stmt->bind_param("i", $idGenero);
        $stmt->execute();
        $stmt->close();

        return true;
    } finally {
        CloseDB($conn);
    }
}

/* CRUD PÉLICULAS */

function ConsultarPeliculasModel()
{
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare("CALL spGetPeliculas()");
        $stmt->execute();

        $resultado = $stmt->get_result();
        $peliculas = [];

        while ($fila = $resultado->fetch_assoc()) {
            $peliculas[] = $fila;
        }

        $resultado->free();
        $stmt->close();

        return $peliculas;
    } finally {
        CloseDB($conn);
    }
}

function ConsultarPeliculaPorIdModel($idPelicula)
{
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare("CALL spGetPeliculaById(?)");
        $stmt->bind_param("i", $idPelicula);
        $stmt->execute();

        $resultadoPelicula = $stmt->get_result();
        $pelicula = $resultadoPelicula->fetch_assoc();
        $resultadoPelicula->free();

        $generos = [];

        if ($stmt->more_results()) {
            $stmt->next_result();

            $resultadoGeneros = $stmt->get_result();

            while ($fila = $resultadoGeneros->fetch_assoc()) {
                $generos[] = $fila;
            }

            $resultadoGeneros->free();
        }

        $stmt->close();

        if (!$pelicula) {
            return null;
        }

        $pelicula['GenerosSeleccionados'] = $generos;

        return $pelicula;
    } finally {
        CloseDB($conn);
    }
}

function RegistrarPeliculaModel(
    $titulo,
    $sinopsis,
    $duracion,
    $clasificacion,
    $fechaEstreno,
    $urlTrailer,
    $urlPoster,
    $idioma,
    $generos
) {
    $conn = OpenDB();

    try {
        $conn->begin_transaction();

        // Registrar la película
        $stmt = $conn->prepare(
            "CALL spAddPelicula(?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssisssss",
            $titulo,
            $sinopsis,
            $duracion,
            $clasificacion,
            $fechaEstreno,
            $urlTrailer,
            $urlPoster,
            $idioma
        );

        $stmt->execute();

        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();

        if (!$fila || !isset($fila['ID_Pelicula'])) {
            throw new Exception(
                'No se pudo obtener el ID de la película registrada.'
            );
        }

        $idPelicula = (int) $fila['ID_Pelicula'];

        $resultado->free();
        $stmt->close();

        LimpiarResultadosPendientes($conn);

        // Asociar los géneros seleccionados
        if (!empty($generos) && is_array($generos)) {

            foreach ($generos as $idGenero) {
                $idGenero = (int) $idGenero;

                $stmtGenero = $conn->prepare(
                    "CALL spAddGeneroToPelicula(?, ?)"
                );

                $stmtGenero->bind_param(
                    "ii",
                    $idPelicula,
                    $idGenero
                );

                $stmtGenero->execute();
                $stmtGenero->close();

                LimpiarResultadosPendientes($conn);
            }
        }

        $conn->commit();

        return $idPelicula;
    } catch (Throwable $e) {
        $conn->rollback();
        throw $e;
    } finally {
        CloseDB($conn);
    }
}

function ActualizarPeliculaModel(
    $idPelicula,
    $titulo,
    $sinopsis,
    $duracion,
    $clasificacion,
    $fechaEstreno,
    $urlTrailer,
    $urlPoster,
    $estado,
    $idioma
) {
    $conn = OpenDB();

    try {

        $stmt = $conn->prepare(
            "CALL spUpdatePelicula(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"

        );

        $stmt->bind_param(
            "ississssis",
            $idPelicula,
            $titulo,
            $sinopsis,
            $duracion,
            $clasificacion,
            $fechaEstreno,
            $urlTrailer,
            $urlPoster,
            $estado,
            $idioma
        );

        $stmt->execute();
        $stmt->close();

        return true;
    } finally {
        CloseDB($conn);
    }
}


function CambiarEstadoPeliculaModel($idPelicula, $estado)
{
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare(
            "CALL spChangeEstadoPelicula(?, ?)"
        );

        $stmt->bind_param(
            "ii",
            $idPelicula,
            $estado
        );

        $stmt->execute();
        $stmt->close();

        return true;
    } finally {
        CloseDB($conn);
    }
}

function EliminarPeliculaModel($idPelicula)
{
    $conn = OpenDB();

    try {
        $stmt = $conn->prepare("CALL spDeletePelicula(?)");
        $stmt->bind_param("i", $idPelicula);
        $stmt->execute();
        $stmt->close();

        return true;
    } finally {
        CloseDB($conn);
    }
}

function LimpiarResultadosPendientes($conn)
{
    while ($conn->more_results()) {
        $conn->next_result();

        if ($resultado = $conn->store_result()) {
            $resultado->free();
        }
    }
}
