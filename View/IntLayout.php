<?php

function Navbar()
{
    echo '
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
    ';
}


function ImportCSS()
{
    echo '
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/style.css">
    ';
}


function ImportJS()
{
    echo '
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/main.js"></script>
    ';
}


function Footer()
{
    echo '
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
    ';
}
