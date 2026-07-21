<?php

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/ExtLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/IntLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Model/CineModel.php';

$cines = ConsultarCinesModel();
$cineEditar = null;

if (isset($_GET['editarCine'])) {

    $idCine = filter_input(
        INPUT_GET,
        'editarCine',
        FILTER_VALIDATE_INT
    );

    if ($idCine) {
        $cineEditar = ConsultarCinePorIdModel($idCine);
    }
}

function Escapar($valor)
{
    return htmlspecialchars(
        (string)$valor,
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
    content="width=device-width, initial-scale=1.0">

<title>Administración de Cines</title>

<?php ImportCSS(); ?>

</head>

<body>

<?php Navbar(); ?>

<main class="seccion">

<div class="container">

<div class="text-center mb-5">

<h1 class="titulo-seccion">
Administración de Cines
</h1>

<p class="texto-seccion">
Registrar y administrar los cines disponibles.
</p>

</div>

<?php if(isset($_SESSION['Mensaje'])): ?>

<?php
$tipo =
($_SESSION['TipoMensaje'] ?? 'success') == 'danger'
? 'danger'
: 'success';
?>

<div class="alert alert-<?= $tipo ?> alert-dismissible fade show">

<?= Escapar($_SESSION['Mensaje']) ?>

<button
type="button"
class="btn-close"
data-bs-dismiss="alert">
</button>

</div>

<?php
unset($_SESSION['Mensaje']);
unset($_SESSION['TipoMensaje']);
?>

<?php endif; ?>

<div class="row">

<div class="col-lg-5">

<div class="promo-card">

<?php if($cineEditar): ?>

<h3>Actualizar Cine</h3>

<form
action="../Controller/CineController.php"
method="POST">

<input
type="hidden"
name="idCine"
value="<?= Escapar($cineEditar['ID_Cine']) ?>">

<?php else: ?>

<h3>Registrar Cine</h3>

<form
action="../Controller/CineController.php"
method="POST">

<?php endif; ?>

<div class="mb-3">

<label>Nombre</label>

<input
type="text"
class="form-control"
name="nombre"
required
value="<?= Escapar($cineEditar['Nombre'] ?? '') ?>">

</div>

<div class="mb-3">

<label>Dirección</label>

<input
type="text"
class="form-control"
name="direccion"
required
value="<?= Escapar($cineEditar['Direccion'] ?? '') ?>">

</div>

<div class="mb-3">

<label>Ciudad</label>

<input
type="text"
class="form-control"
name="ciudad"
required
value="<?= Escapar($cineEditar['Ciudad'] ?? '') ?>">

</div>

<div class="mb-3">

<label>Teléfono</label>

<input
type="text"
class="form-control"
name="telefono"
required
value="<?= Escapar($cineEditar['Telefono'] ?? '') ?>">

</div>

<div class="mb-3">

<label>Correo</label>

<input
type="email"
class="form-control"
name="correo"
required
value="<?= Escapar($cineEditar['Correo'] ?? '') ?>">

</div>

<?php if($cineEditar): ?>

<button
class="btn btn-dorado"
type="submit"
name="btnActualizarCine">

Actualizar

</button>

<a
href="Cines.php"
class="btn btn-secondary">

Cancelar

</a>

<?php else: ?>

<button
class="btn btn-dorado"
type="submit"
name="btnRegistrarCine">

Registrar

</button>

<?php endif; ?>

</form>

</div>

</div>

<div class="col-lg-7">

<div class="promo-card">

<h3>Cines Registrados</h3>

<div class="table-responsive">

<table
class="table table-dark table-hover">

<thead>

<tr>

<th>ID</th>
<th>Nombre</th>
<th>Ciudad</th>
<th>Teléfono</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

<?php if(!empty($cines)): ?>

<?php foreach($cines as $cine): ?>

<tr>

<td><?= Escapar($cine['ID_Cine']) ?></td>

<td><?= Escapar($cine['Nombre']) ?></td>

<td><?= Escapar($cine['Ciudad']) ?></td>

<td><?= Escapar($cine['Telefono']) ?></td>

<td>

<div class="d-flex gap-2">

<a
href="Cines.php?editarCine=<?= $cine['ID_Cine'] ?>"
class="btn btn-warning btn-sm">

Editar

</a>

<form
action="../Controller/CineController.php"
method="POST"
onsubmit="return confirm('¿Desea eliminar este cine?');">

<input
type="hidden"
name="idCine"
value="<?= $cine['ID_Cine'] ?>">

<button
type="submit"
name="btnEliminarCine"
class="btn btn-danger btn-sm">

Eliminar

</button>

</form>

</div>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="5" class="text-center">

No existen cines registrados.

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

</div>

</main>

<?php
Footer();
ImportJS();
?>

</body>
</html>