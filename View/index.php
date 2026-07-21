<?php

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/ExtLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/IntLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Model/PeliculaModel.php';

$peliculas = ConsultarPeliculasInicioModel();

function EscaparInicio($valor)
{
    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        'UTF-8'
    );
}
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Golden Frame Cinemas</title>

    <?php ImportCSS(); ?>
</head>

<body>

<?php Navbar(); ?>

<!-- INICIO -->

<header id="inicio" class="hero">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-md-6 text-center text-md-start">

                <h1>Vive la magia del cine</h1>

                <p>
                    Disfruta los mejores estrenos, salas cómodas y una
                    experiencia premium en Golden Frame Cinemas.
                </p>

                <a
                    href="#cartelera"
                    class="btn btn-dorado"
                >
                    Ver cartelera
                </a>

            </div>

            <div class="col-md-6 text-center mt-4 mt-md-0">

                <img
                    src="https://image.tmdb.org/t/p/w780/8cdWjvZQUExUUTzyp4t6EDMubfO.jpg"
                    alt="Película destacada"
                    class="img-fluid hero-img"
                >

            </div>

        </div>

    </div>

</header>

<!-- CARTELERA -->

<section id="cartelera" class="seccion">

    <div class="container">

        <h2 class="titulo-seccion">
            Cartelera semanal
        </h2>

        <p class="texto-seccion">
            Películas disponibles esta semana en nuestras salas.
        </p>

        <div class="text-center mb-5">

            <button
                type="button"
                class="btn btn-filtro active"
                onclick="filtrarPeliculas('Todas')"
            >
                Todas
            </button>

            <button
                type="button"
                class="btn btn-filtro"
                onclick="filtrarPeliculas('Acción')"
            >
                Acción
            </button>

            <button
                type="button"
                class="btn btn-filtro"
                onclick="filtrarPeliculas('Animación')"
            >
                Animación
            </button>

            <button
                type="button"
                class="btn btn-filtro"
                onclick="filtrarPeliculas('Drama')"
            >
                Drama
            </button>

            <button
                type="button"
                class="btn btn-filtro"
                onclick="filtrarPeliculas('Terror')"
            >
                Terror
            </button>

        </div>

        <div
            class="row"
            id="contenedorPeliculas"
        >

            <?php if (!empty($peliculas)): ?>

                <?php foreach ($peliculas as $pelicula): ?>

                    <?php
                    $idPelicula =
                        (int) ($pelicula['ID_Pelicula'] ?? 0);

                    $titulo =
                        $pelicula['Titulo'] ?? 'Película';

                    $poster =
                        $pelicula['URLPoster'] ?? '';

                    $sinopsis =
                        trim($pelicula['Sinopsis'] ?? '');

                    $genero =
                        $pelicula['Genero']
                        ?? $pelicula['NombreGenero']
                        ?? '';
                    ?>

                    <div
                        class="col-lg-3 col-md-4 col-sm-6 mb-4 pelicula-item"
                        data-genero="<?= EscaparInicio($genero) ?>"
                    >

                        <a
                            href="/WebCS_G6_Proyecto/View/Pelicula.php?id_pelicula=<?= $idPelicula ?>"
                            class="text-decoration-none text-reset"
                        >

                            <div class="promo-card h-100">

                                <img
                                    src="<?= EscaparInicio($poster) ?>"
                                    class="img-fluid rounded mb-3"
                                    alt="<?= EscaparInicio($titulo) ?>"
                                >

                                <h5 class="text-center">
                                    <?= EscaparInicio($titulo) ?>
                                </h5>

                                <?php if ($sinopsis !== ''): ?>

                                    <p class="text-center">
                                        <?= EscaparInicio(
                                            mb_strimwidth(
                                                $sinopsis,
                                                0,
                                                95,
                                                '...'
                                            )
                                        ) ?>
                                    </p>

                                <?php endif; ?>

                            </div>

                        </a>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="col-12">

                    <div class="alert alert-warning text-center">
                        No hay películas disponibles en cartelera.
                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</section>

<!-- PROMOCIONES -->

<section id="promociones" class="seccion promociones">

    <div class="container">

        <h2 class="titulo-seccion">
            Promociones
        </h2>

        <div class="row mt-4">

            <div class="col-md-4 mb-3">

                <div class="promo-card">

                    <h3>Martes Dorado</h3>

                    <p>
                        Entradas con precio especial todos los martes.
                    </p>

                </div>

            </div>

            <div class="col-md-4 mb-3">

                <div class="promo-card">

                    <h3>Combo Pareja</h3>

                    <p>
                        2 bebidas, palomitas grandes y dulces seleccionados.
                    </p>

                </div>

            </div>

            <div class="col-md-4 mb-3">

                <div class="promo-card">

                    <h3>Estrenos Premium</h3>

                    <p>
                        Disfruta los estrenos más esperados en salas
                        especiales a un 2x1 los jueves.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- NOSOTROS -->

<section id="nosotros" class="seccion nosotros">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-md-6 text-center mb-4 mb-md-0">

                <img
                    src="images/logo-golden-frame.jpeg"
                    alt="Logo Golden Frame Cinemas"
                    class="img-fluid nosotros-img"
                >

            </div>

            <div class="col-md-6">

                <h2>Golden Frame Cinemas</h2>

                <p>
                    Somos un cine creado para disfrutar películas con una
                    experiencia cómoda, moderna y elegante. Nuestro estilo
                    combina el negro con detalles dorados para brindar una
                    experiencia premium.
                </p>

                <a
                    href="#contacto"
                    class="btn btn-dorado"
                >
                    Conócenos
                </a>

            </div>

        </div>

    </div>

</section>

<?php
Footer();
ImportJS();
?>

</body>
</html>