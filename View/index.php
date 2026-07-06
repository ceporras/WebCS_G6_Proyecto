<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Golden Frame Cinemas</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>


  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="index.html">
        <img src="images/logo-golden-frame.jpeg" alt="Logo Golden Frame" class="logo">
        <span class="ms-2">Golden Frame Cinemas</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="menuPrincipal">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item"><a class="nav-link" href="#inicio">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="#cartelera">Cartelera</a></li>
          <li class="nav-item"><a class="nav-link" href="#promociones">Promociones</a></li>
          <li class="nav-item"><a class="nav-link" href="#nosotros">Nosotros</a></li>
          <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
          <li class="nav-item">
            <a class="login-icon" href="IniciarSesion.php" title="Iniciar sesión">👤</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

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

      <div class="row" id="contenedorPeliculas">
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
  <footer id="contacto" class="footer">
    <div class="container">
      <div class="row text-center">
        <div class="col-md-4 mb-4">
          <h4>Contacto</h4>
          <p>📍 Costa Rica</p>
          <p>📞 +506 8888-8888</p>
          <p>✉️ cine@goldenframe.com</p>
        </div>

        <div class="col-md-4 mb-4">
          <h4>Golden Frame</h4>
          <p>Tu cine para vivir historias en pantalla grande.</p>
          <div class="redes">
            <a href="#">f</a>
            <a href="#">ig</a>
            <a href="#">x</a>
          </div>
        </div>

        <div class="col-md-4 mb-4">
          <h4>Horario</h4>
          <p>Lunes a Domingo</p>
          <p>11:00 AM - 11:00 PM</p>
        </div>
      </div>

      <p class="text-center mb-0">© 2026 Golden Frame Cinemas</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>
