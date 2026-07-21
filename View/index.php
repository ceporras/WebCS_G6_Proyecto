<?php

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/ExtLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/IntLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
. '/WebCS_G6_Proyecto/Model/PeliculaModel.php';

$peliculas = ConsultarPeliculasInicioModel();

?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Golden Frame Cinemas</title>

  <?php
    ImportCSS();
  ?>
</head>
<body>


  <?php
    Navbar(); 
  ?>

  <!-- INICIO -->
  <header id="inicio" class="hero">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-start">
          <h1>Vive la magia del cine</h1>
          <p>Disfruta los mejores estrenos, salas cómodas y una experiencia premium en Golden Frame Cinemas.</p>
          <a href="#cartelera" class="btn btn-dorado">Ver cartelera</a>
        </div>
        <div class="col-md-6 text-center mt-4 mt-md-0">
          <img src="https://image.tmdb.org/t/p/w780/8cdWjvZQUExUUTzyp4t6EDMubfO.jpg" alt="Película destacada" class="img-fluid hero-img">
        </div>
      </div>
    </div>
  </header>

  <!-- CARTELERA -->
  <section id="cartelera" class="seccion">
    <div class="container">
      <h2 class="titulo-seccion">Cartelera semanal</h2>
      <p class="texto-seccion">Películas disponibles esta semana en nuestras salas.</p>

      <div class="text-center mb-5">
        <button class="btn btn-filtro active" onclick="filtrarPeliculas('Todas')">Todas</button>
        <button class="btn btn-filtro" onclick="filtrarPeliculas('Acción')">Acción</button>
        <button class="btn btn-filtro" onclick="filtrarPeliculas('Animación')">Animación</button>
        <button class="btn btn-filtro" onclick="filtrarPeliculas('Drama')">Drama</button>
        <button class="btn btn-filtro" onclick="filtrarPeliculas('Terror')">Terror</button>
      </div>

      <div class="row">

<?php foreach($peliculas as $pelicula): ?>

<div class="col-md-3 mb-4">

<div class="promo-card h-100">

<a
href="Pelicula.php?id_pelicula=<?= $pelicula['ID_Pelicula'] ?>"
style="text-decoration:none;color:inherit;">

<img
src="<?= $pelicula['URLPoster'] ?>"
class="img-fluid rounded mb-3"
alt="<?= htmlspecialchars($pelicula['Titulo']) ?>">

<h5 class="text-center">

<?= htmlspecialchars($pelicula['Titulo']) ?>

</h5>

<p class="text-center">

<?= substr($pelicula['Sinopsis'],0,90) ?>...

</p>

</a>

</div>

</div>

<?php endforeach; ?>

</div>
    </div>
  </section>

  <!-- PROMOCIONES -->
  <section id="promociones" class="seccion promociones">
    <div class="container">
      <h2 class="titulo-seccion">Promociones</h2>

      <div class="row mt-4">
        <div class="col-md-4 mb-3">
          <div class="promo-card">
            <h3>Martes Dorado</h3>
            <p>Entradas con precio especial todos los martes.</p>
          </div>
        </div>

        <div class="col-md-4 mb-3">
          <div class="promo-card">
            <h3>Combo Pareja</h3>
            <p>2 bebidas, palomitas grandes y dulces seleccionados.</p>
          </div>
        </div>

        <div class="col-md-4 mb-3">
          <div class="promo-card">
            <h3>Estrenos Premium</h3>
            <p>Disfruta los estrenos más esperados en salas especiales a un 2X1 los Jueves .</p>
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
          <img src="images/logo-golden-frame.jpeg" alt="Logo Golden Frame Cinemas" class="img-fluid nosotros-img">
        </div>

        <div class="col-md-6">
          <h2>Golden Frame Cinemas</h2>
          <p>
            Somos un cine creado para disfrutar películas con una experiencia cómoda,
            moderna y elegante. Nuestro estilo combina el negro con detalles dorados
            para dar una sensación premium desde la página principal donde compras una experiencia en el cine.
          </p>
          <a href="#contacto" class="btn btn-dorado">Conócenos</a>
        </div>
      </div>
    </div>
  </section>

  <!-- CONTACTO -->
  <?php
    Footer();
    ImportJS();
  ?>

  
</body>
</html>
