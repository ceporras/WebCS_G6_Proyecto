<?php

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/ExtLayout.php';

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/View/IntLayout.php';
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Administrar funciones | Golden Frame Cinemas</title>

    <?php ImportCSS(); ?>

    <style>
        .campo-obligatorio::after {
            content: " *";
            color: #dc3545;
        }

        .funciones-formulario {
            text-align: left;
        }

        .funciones-formulario .form-label {
            color: #ffffff;
            font-weight: 600;
        }

        .funciones-formulario .form-text {
            color: #bfbfbf;
        }

        .funciones-tabla {
            min-width: 1100px;
        }

        .funciones-tabla th,
        .funciones-tabla td {
            vertical-align: middle;
            white-space: nowrap;
        }

        .acciones-tabla {
            min-width: 155px;
        }

        .contenedor-cargando {
            display: none;
            color: #ffffff;
            text-align: center;
            padding: 25px;
        }

        .mensaje-vacio {
            text-align: center;
            color: #cccccc !important;
            padding: 25px !important;
        }

        .modal-content-funcion {
            background-color: #111111;
            color: #ffffff;
            border: 1px solid #d8b65a;
        }

        .modal-content-funcion .modal-header {
            border-bottom-color: #333333;
        }

        .modal-content-funcion .modal-footer {
            border-top-color: #333333;
        }

        .modal-content-funcion .btn-close {
            filter: invert(1);
        }

        .input-group-text {
            background-color: #d8b65a;
            border-color: #d8b65a;
            color: #000000;
            font-weight: bold;
        }
    </style>
</head>

<body>

<?php Navbar(); ?>

<main class="seccion">

    <div class="container">

        <div class="text-center mb-5">

            <h1 class="titulo-seccion">
                Administración de funciones
            </h1>

            <p class="texto-seccion">
                Registra, consulta y administra las funciones
                programadas en Golden Frame Cinemas.
            </p>

            <a
                href="AdmPeliculas.php"
                class="btn btn-dorado"
            >
                Administrar películas
            </a>

        </div>

        <div
            id="contenedorAlerta"
            aria-live="polite"
        ></div>

        <section class="promo-card mb-5 funciones-formulario">

            <h2
                id="tituloFormulario"
                class="text-center mb-4"
            >
                Registrar función
            </h2>

            <form
                id="formFuncion"
                autocomplete="off"
            >

                <input
                    type="hidden"
                    id="idFuncion"
                    name="idFuncion"
                    value=""
                >

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label
                            for="idPelicula"
                            class="form-label campo-obligatorio"
                        >
                            Película
                        </label>

                        <select
                            class="form-select"
                            id="idPelicula"
                            name="idPelicula"
                            required
                        >
                            <option value="">
                                Seleccione una película
                            </option>
                        </select>

                        <div class="form-text">
                            Solo se muestran películas activas.
                        </div>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label
                            for="idCine"
                            class="form-label campo-obligatorio"
                        >
                            Cine
                        </label>

                        <select
                            class="form-select"
                            id="idCine"
                            name="idCine"
                            required
                        >
                            <option value="">
                                Seleccione un cine
                            </option>
                        </select>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label
                            for="idSala"
                            class="form-label campo-obligatorio"
                        >
                            Sala
                        </label>

                        <select
                            class="form-select"
                            id="idSala"
                            name="idSala"
                            required
                            disabled
                        >
                            <option value="">
                                Primero seleccione un cine
                            </option>
                        </select>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label
                            for="horaInicio"
                            class="form-label campo-obligatorio"
                        >
                            Fecha y hora de inicio
                        </label>

                        <input
                            type="datetime-local"
                            class="form-control"
                            id="horaInicio"
                            name="horaInicio"
                            required
                        >

                        <div class="form-text">
                            La hora final se calcula según la duración de la película.
                        </div>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label
                            for="precio"
                            class="form-label campo-obligatorio"
                        >
                            Precio
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                ₡
                            </span>

                            <input
                                type="number"
                                class="form-control"
                                id="precio"
                                name="precio"
                                min="1"
                                step="0.01"
                                placeholder="Ejemplo: 3500"
                                required
                            >

                        </div>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label
                            for="idioma"
                            class="form-label campo-obligatorio"
                        >
                            Idioma
                        </label>

                        <select
                            class="form-select"
                            id="idioma"
                            name="idioma"
                            required
                        >
                            <option value="">
                                Seleccione un idioma
                            </option>

                            <option value="Español">
                                Español
                            </option>

                            <option value="Inglés">
                                Inglés
                            </option>

                            <option value="Español subtitulado">
                                Español subtitulado
                            </option>

                            <option value="Inglés subtitulado">
                                Inglés subtitulado
                            </option>

                            <option value="Doblada al español">
                                Doblada al español
                            </option>
                        </select>

                    </div>

                    <div class="col-md-2 mb-3">

                        <label
                            for="formato"
                            class="form-label campo-obligatorio"
                        >
                            Formato
                        </label>

                        <select
                            class="form-select"
                            id="formato"
                            name="formato"
                            required
                        >
                            <option value="">
                                Seleccione
                            </option>

                            <option value="2D">
                                2D
                            </option>

                            <option value="3D">
                                3D
                            </option>

                            <option value="IMAX">
                                IMAX
                            </option>

                            <option value="4DX">
                                4DX
                            </option>
                        </select>

                    </div>

                </div>

                <div class="d-flex justify-content-center gap-2 flex-wrap mt-3">

                    <button
                        type="submit"
                        class="btn btn-dorado"
                        id="btnGuardar"
                    >
                        Guardar función
                    </button>

                    <button
                        type="button"
                        class="btn btn-secondary d-none"
                        id="btnCancelar"
                    >
                        Cancelar modificación
                    </button>

                </div>

            </form>

        </section>

        <section class="promo-card">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                <h2 class="mb-0">
                    Funciones registradas
                </h2>

                <button
                    type="button"
                    class="btn btn-dorado btn-sm"
                    id="btnActualizarTabla"
                >
                    Actualizar
                </button>

            </div>

            <div
                id="contenedorCargando"
                class="contenedor-cargando"
            >
                <div
                    class="spinner-border text-warning"
                    role="status"
                >
                    <span class="visually-hidden">
                        Cargando...
                    </span>
                </div>

                <p class="mt-3 mb-0">
                    Cargando funciones...
                </p>
            </div>

            <div class="table-responsive">

                <table
                    class="table table-dark table-hover align-middle funciones-tabla"
                    id="tablaFunciones"
                >

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Película</th>
                            <th>Cine</th>
                            <th>Sala</th>
                            <th>Inicio</th>
                            <th>Final</th>
                            <th>Precio</th>
                            <th>Idioma</th>
                            <th>Formato</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody id="cuerpoTablaFunciones">
                        <tr>
                            <td
                                colspan="10"
                                class="mensaje-vacio"
                            >
                                Cargando funciones...
                            </td>
                        </tr>
                    </tbody>

                </table>

            </div>

        </section>

    </div>

</main>

<div
    class="modal fade"
    id="modalEliminarFuncion"
    tabindex="-1"
    aria-labelledby="tituloModalEliminar"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content modal-content-funcion">

            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="tituloModalEliminar"
                >
                    Confirmar eliminación
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar"
                ></button>

            </div>

            <div class="modal-body">

                <p>
                    ¿Está seguro de que desea eliminar la función seleccionada?
                </p>

                <p class="text-danger mb-0">
                    Esta acción no se puede deshacer.
                </p>

                <input
                    type="hidden"
                    id="idFuncionEliminar"
                    value=""
                >

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancelar
                </button>

                <button
                    type="button"
                    class="btn btn-danger"
                    id="btnConfirmarEliminar"
                >
                    Eliminar
                </button>

            </div>

        </div>

    </div>

</div>

<?php
Footer();
ImportJS();
?>

<script src="/WebCS_G6_Proyecto/View/js/funciones.js"></script>

</body>
</html>