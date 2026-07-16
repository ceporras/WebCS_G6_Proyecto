<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/WebCS_G6_Proyecto/Model/PeliculaModel.php';

if (isset($_GET['id_pelicula'])) {

    $ID_Pelicula = (int) $_GET['id_pelicula'];
    $pelicula = getPelicula($ID_Pelicula)->fetch_assoc();
    $funciones = getFunciones($ID_Pelicula);
    
}/*else{
    redirect somewhere else
}*/