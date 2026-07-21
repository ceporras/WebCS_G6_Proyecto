<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/Model/PeliculaModel.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['id_pelicula'])) {

    $ID_Pelicula = (int) $_GET['id_pelicula'];
    $pelicula = getPelicula($ID_Pelicula)->fetch_assoc();
    $funciones = getFuncionesByPelicula($ID_Pelicula);
} else {
    //si el URL no tiene ID de pelicula, no puedo estar aqui
    header("Location: ../View/index.php");
    exit();
}

//ajustar formato de duracion de pelicula
function FormatDuracion($minutos) {
    $horas = intdiv($minutos, 60);
    $mins = $minutos % 60;

    if ($horas > 0 && $mins > 0) {
        return "{$horas}h {$mins}min";
    } elseif ($horas > 0) {
        return "{$horas}h";
    } else {
        return "{$mins}min";
    }
}

/* Registro de género */

if (isset($_POST['btnRegistrarGenero'])) {

    try {

        $nombre = trim($_POST['nombreGenero'] ?? '');

        if ($nombre === '') {

            $_SESSION['Mensaje'] = 'Por favor ingrese el nombre del género.';
            $_SESSION['TipoMensaje'] = 'danger';
        } else {

            RegistrarGeneroModel($nombre);

            $_SESSION['Mensaje'] = 'El género se registró de manera exitosa.';
            $_SESSION['TipoMensaje'] = 'success';
        }
    } catch (mysqli_sql_exception $e) {

        if ($e->getCode() === 1062) {

            $_SESSION['Mensaje'] = 'Ese género ya se encuentra registrado, por favor intente con otro.';
        } else {

            $_SESSION['Mensaje'] = 'Le informamos que no se pudo registrar el género.';
        }

        $_SESSION['TipoMensaje'] = 'danger';
    }

    header('Location: ../View/Generos.php');
    exit;
}


/*Actualizar el género*/

if (isset($_POST['btnActualizarGenero'])) {
    try {
        $idGenero = filter_input(
            INPUT_POST,
            'idGenero',
            FILTER_VALIDATE_INT
        );

        $nombre = trim($_POST['nombreGenero'] ?? '');

        if (!$idGenero || $nombre === '') {
            throw new Exception('Datos del género inválidos.');
        }

        ActualizarGeneroModel($idGenero, $nombre);

        $_SESSION['Mensaje'] = 'El género se actualizó correctamente.';
        $_SESSION['TipoMensaje'] = 'success';
    } catch (Throwable $e) {
        $_SESSION['Mensaje'] = 'No se pudo realizar la actualización del género.';
        $_SESSION['TipoMensaje'] = 'danger';
    }

    header('Location: ../View/Generos.php');
    exit;
}

/*Eliminar el género*/

if (isset($_POST['btnEliminarGenero'])) {
    try {
        $idGenero = filter_input(
            INPUT_POST,
            'idGenero',
            FILTER_VALIDATE_INT
        );

        if (!$idGenero) {
            throw new Exception('ID de género inválido.');
        }

        EliminarGeneroModel($idGenero);

        $_SESSION['Mensaje'] = 'El género fue eliminado correctamente.';
        $_SESSION['TipoMensaje'] = 'success';
    } catch (Throwable $e) {
        $_SESSION['Mensaje'] = 'No se pudo eliminar el género indicado.';
        $_SESSION['TipoMensaje'] = 'danger';
    }

    header('Location: ../View/Generos.php');
    exit;
}

/*Registro de péliculas*/

if (isset($_POST['btnRegistrarPelicula'])) {
    try {
        $titulo = trim($_POST['titulo'] ?? '');
        $sinopsis = trim($_POST['sinopsis'] ?? '');
        $duracion = filter_input(
            INPUT_POST,
            'duracion',
            FILTER_VALIDATE_INT
        );

        $clasificacion = trim($_POST['clasificacion'] ?? '');
        $fechaEstreno = trim($_POST['fechaEstreno'] ?? '');
        $urlTrailer = trim($POST['urlTrailer'] ?? '');
        $urlPoster = trim($_POST['urlPoster'] ?? '');
        $idioma = trim($_POST['idioma'] ?? '');
        $generos = $_POST['generos'] ?? [];

        if (
            $titulo === '' ||
            $sinopsis === '' ||
            !$duracion ||
            $duracion <= 0 ||
            $clasificacion === '' ||
            $fechaEstreno === '' ||
            $idioma === ''
        ) {
            throw new Exception('Se deben completar todos los campos obligatorios.');
        }

        if (!is_array($generos) || count($generos) === 0) {
            throw new Exception('Se debe seleecionar al menos un género.');
        }

        RegistrarPeliculaModel(
            $titulo,
            $sinopsis,
            $duracion,
            $clasificacion,
            $fechaEstreno,
            $urlTrailer,
            $urlPoster,
            $idioma,
            $generos
        );

        $_SESSION['Mensaje'] = 'La película fue registrada de manera exitosa.';
        $_SESSION['TipoMensaje'] = 'sucess';
    } catch (Throwable $e) {
        $_SESSION['Mensaje'] = $e->getMessage();
        $_SESSION['TipoMensaje'] = 'danger';
    }

    header('Location: ../View/Peliculas.php');
    exit;
}

/*Actualización de películas*/

if (isset($_POST['btnActualizarPelicula'])) {
    try {
        $idPelicula = filter_input(
            INPUT_POST,
            'idPelicula',
            FILTER_VALIDATE_INT
        );

        $titulo = trim($_POST['titulo'] ?? '');
        $sinopsis = trim($_POST['sinopsis'] ?? '');

        $duracion = filter_input(
            INPUT_POST,
            'duracion',
            FILTER_VALIDATE_INT
        );

        $clasificacion = trim($_POST['clasificacion'] ?? '');
        $fechaEstreno = trim($_POST['fechaEstreno'] ?? '');
        $urlTrailer = trim($_POST['urlTrailer'] ?? '');
        $urlPoster = trim($_POST['urlPoster'] ?? '');
        $idioma = trim($_POST['idioma'] ?? '');

        $estado = filter_input(
            INPUT_POST,
            'estado',
            FILTER_VALIDATE_INT
        );

        if (
            !$idPelicula ||
            $titulo === '' ||
            !$duracion ||
            $duracion <= 0
        ) {
            throw new Exception('Le informamos que los datos de la película son inválidos.');
        }

        if ($estado !== 0 && $estado !== 1) {
            $estado = 1;
        }

        ActualizarPeliculaModel(
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

        $_SESSION['Mensaje'] = 'La pelicula se actualizó de manera exitosa.';
        $_SESSION['TipoMensaje'] = 'success';
    } catch (Throwable $e) {
        $_SESSION['Mensaje'] = 'No se pudo realizar la actualización de la película.';
        $_SESSION['TipoMensaje'] = 'danger';
    }

    header('Location: ../View/Peliculas.php');
    exit;
}

/* Cambiar estado */

if (isset($_POST['btnCambiarEstadoPelicula'])) {
    try {
        $idPelicula = filter_input(
            INPUT_POST,
            'idPelicula',
            FILTER_VALIDATE_INT
        );

        $estado = filter_input(
            INPUT_POST,
            'estado',
            FILTER_VALIDATE_INT
        );

        if (!$idPelicula || ($estado !== 0 && $estado !== 1)) {
            throw new Exception('Los datos son inválidos.');
        }

        CambiarEstadoPeliculaModel($idPelicula, $estado);

        $_SESSION['Mensaje'] = 'El estado se actualizó de forma exitosa.';
        $_SESSION['TipoMensaje'] = 'success';
    } catch (Throwable $e) {
        $_SESSION['Mensaje'] = 'No se pudo realizar el cambio de estado.';
        $_SESSION['TipoMensaje'] = 'danger';
    }

    header('Location: ../View/Peliculas.php');
    exit;
}

/* Eliminar Películas */

if (isset($_POST['btnEliminarPelicula'])) {
    try {
        $idPelicula = filter_input(
            INPUT_POST,
            'idPelicula',
            FILTER_VALIDATE_INT
        );

        if (!$idPelicula) {
            throw new Exception('ID de película inválido.');
        }

        EliminarPeliculaModel($idPelicula);

        $_SESSION['Mensaje'] = 'La película fue eliminada correctamente.';
        $_SESSION['TipoMensaje'] = 'success';
    } catch (Throwable $e) {
        $_SESSION['Mensaje'] = 'No se pudo eliminar la película.';
        $_SESSION['TipoMensaje'] = 'danger';
    }

    header('Location: ../View/Peliculas.php');
    exit;
}
