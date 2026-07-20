<?php

function Navbar()
{
    echo '
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">

            <a
                class="navbar-brand d-flex align-items-center"
                href="/WebCS_G6_Proyecto/View/index.php"
            >
                <img
                    src="/WebCS_G6_Proyecto/View/images/logo-golden-frame.jpeg"
                    alt="Logo Golden Frame"
                    class="logo"
                >

                <span class="ms-2">
                    Golden Frame Cinemas
                </span>
            </a>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#menuPrincipal"
                aria-controls="menuPrincipal"
                aria-expanded="false"
                aria-label="Mostrar menú"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div
                class="collapse navbar-collapse"
                id="menuPrincipal"
            >
                <ul class="navbar-nav ms-auto align-items-lg-center">

                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="/WebCS_G6_Proyecto/View/index.php#inicio"
                            aria-current="page"
                        >
                            Inicio
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="/WebCS_G6_Proyecto/View/index.php#cartelera"
                        >
                            Cartelera
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="/WebCS_G6_Proyecto/View/index.php#promociones"
                        >
                            Promociones
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="/WebCS_G6_Proyecto/View/index.php#nosotros"
                        >
                            Nosotros
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="/WebCS_G6_Proyecto/View/index.php#contacto"
                        >
                            Contacto
                        </a>
                    </li>

                    <li class="nav-item dropdown">

                        <a
                            class="nav-link dropdown-toggle"
                            href="javascript:void(0)"
                            id="administracionDropdown"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            Administración
                        </a>

                        <ul
                            class="dropdown-menu dropdown-menu-dark"
                            aria-labelledby="administracionDropdown"
                        >
                            <li>
                                <a
                                    class="dropdown-item"
                                    href="/WebCS_G6_Proyecto/View/Peliculas.php"
                                >
                                    Películas
                                </a>
                            </li>

                            <li>
                                <a
                                    class="dropdown-item"
                                    href="/WebCS_G6_Proyecto/View/Generos.php"
                                >
                                    Géneros
                                </a>
                            </li>
                        </ul>

                    </li>

                    <li class="nav-item ms-lg-3">
                        <a
                            class="login-icon"
                            href="/WebCS_G6_Proyecto/View/IniciarSesion.php"
                            title="Iniciar sesión"
                            aria-label="Iniciar sesión"
                        >
                            👤
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
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

                        <p>
                            Tu cine para vivir historias en pantalla grande.
                        </p>

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

                <p class="text-center mb-0">
                    © 2026 Golden Frame Cinemas
                </p>
            </div>
        </footer>
    ';
}