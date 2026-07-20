"use strict";

const URL_FUNCION_CONTROLLER =
    "/WebCS_G6_Proyecto/Controller/FuncionController.php";

document.addEventListener("DOMContentLoaded", function () {
    inicializarModuloFunciones();
});



async function inicializarModuloFunciones() {
    configurarEventos();
    establecerFechaMinima();
    cambiarEstadoFormulario(false);

    await cargarPeliculas();
    await cargarCines();
    await cargarFunciones();
}



function configurarEventos() {
    const formulario = document.getElementById("formFuncion");
    const selectCine = document.getElementById("idCine");
    const botonCancelar = document.getElementById("btnCancelar");
    const botonActualizarTabla =
        document.getElementById("btnActualizarTabla");
    const botonConfirmarEliminar =
        document.getElementById("btnConfirmarEliminar");

    if (formulario) {
        formulario.addEventListener(
            "submit",
            procesarFormularioFuncion
        );
    }

    if (selectCine) {
        selectCine.addEventListener(
            "change",
            async function () {
                await cargarSalas(this.value);
            }
        );
    }

    if (botonCancelar) {
        botonCancelar.addEventListener(
            "click",
            limpiarFormularioFuncion
        );
    }

    if (botonActualizarTabla) {
        botonActualizarTabla.addEventListener(
            "click",
            cargarFunciones
        );
    }

    if (botonConfirmarEliminar) {
        botonConfirmarEliminar.addEventListener(
            "click",
            eliminarFuncionConfirmada
        );
    }
}


/**
 *
 * @param {string} accion
 * @param {Object|null} datos
 * @param {string} metodo
 * @returns {Promise<Object>}
 */
async function solicitarFuncionController(
    accion,
    datos = null,
    metodo = "GET"
) {
    metodo = metodo.toUpperCase();

    let url = URL_FUNCION_CONTROLLER;
    const opciones = {
        method: metodo,
        headers: {}
    };

    if (metodo === "GET") {
        const parametros = new URLSearchParams();
        parametros.append("accion", accion);

        if (datos) {
            Object.entries(datos).forEach(
                function ([clave, valor]) {
                    if (
                        valor !== null &&
                        valor !== undefined &&
                        valor !== ""
                    ) {
                        parametros.append(clave, valor);
                    }
                }
            );
        }

        url += "?" + parametros.toString();
    } else {
        const formulario = new FormData();
        formulario.append("accion", accion);

        if (datos) {
            Object.entries(datos).forEach(
                function ([clave, valor]) {
                    formulario.append(
                        clave,
                        valor ?? ""
                    );
                }
            );
        }

        opciones.body = formulario;
    }

    const respuesta = await fetch(url, opciones);

    let contenido;

    try {
        contenido = await respuesta.json();
    } catch (error) {
        throw new Error(
            "El servidor devolvió una respuesta que no es JSON válido."
        );
    }

    if (!respuesta.ok || !contenido.exito) {
        throw new Error(
            contenido.mensaje ||
            "No fue posible completar la operación."
        );
    }

    return contenido;
}



async function cargarPeliculas() {
    const selectPelicula =
        document.getElementById("idPelicula");

    if (!selectPelicula) {
        return;
    }

    selectPelicula.innerHTML = `
        <option value="">
            Cargando películas...
        </option>
    `;

    selectPelicula.disabled = true;

    try {
        const respuesta =
            await solicitarFuncionController(
                "consultarPeliculas"
            );

        const peliculas = respuesta.datos || [];

        selectPelicula.innerHTML = `
            <option value="">
                Seleccione una película
            </option>
        `;

        peliculas.forEach(function (pelicula) {
            const opcion = document.createElement("option");

            opcion.value = pelicula.ID_Pelicula;

            opcion.textContent =
                pelicula.Titulo +
                " (" +
                pelicula.Duracion +
                " min)";

            selectPelicula.appendChild(opcion);
        });

        selectPelicula.disabled = false;

        if (peliculas.length === 0) {
            selectPelicula.innerHTML = `
                <option value="">
                    No hay películas activas
                </option>
            `;

            selectPelicula.disabled = true;
        }
    } catch (error) {
        selectPelicula.innerHTML = `
            <option value="">
                Error al cargar películas
            </option>
        `;

        mostrarAlerta(error.message, "danger");
    }
}



async function cargarCines() {
    const selectCine =
        document.getElementById("idCine");

    if (!selectCine) {
        return;
    }

    selectCine.innerHTML = `
        <option value="">
            Cargando cines...
        </option>
    `;

    selectCine.disabled = true;

    try {
        const respuesta =
            await solicitarFuncionController(
                "consultarCines"
            );

        const cines = respuesta.datos || [];

        selectCine.innerHTML = `
            <option value="">
                Seleccione un cine
            </option>
        `;

        cines.forEach(function (cine) {
            const opcion = document.createElement("option");

            opcion.value = cine.ID_Cine;

            opcion.textContent =
                cine.Nombre +
                (cine.Ciudad
                    ? " - " + cine.Ciudad
                    : "");

            selectCine.appendChild(opcion);
        });

        selectCine.disabled = false;

        if (cines.length === 0) {
            selectCine.innerHTML = `
                <option value="">
                    No hay cines registrados
                </option>
            `;

            selectCine.disabled = true;
        }
    } catch (error) {
        selectCine.innerHTML = `
            <option value="">
                Error al cargar cines
            </option>
        `;

        mostrarAlerta(error.message, "danger");
    }
}


/**
 *
 * @param {number|string} idCine
 * @param {number|string|null} idSalaSeleccionada
 */
async function cargarSalas(
    idCine,
    idSalaSeleccionada = null
) {
    const selectSala =
        document.getElementById("idSala");

    if (!selectSala) {
        return;
    }

    if (!idCine) {
        selectSala.innerHTML = `
            <option value="">
                Primero seleccione un cine
            </option>
        `;

        selectSala.disabled = true;
        return;
    }

    selectSala.innerHTML = `
        <option value="">
            Cargando salas...
        </option>
    `;

    selectSala.disabled = true;

    try {
        const respuesta =
            await solicitarFuncionController(
                "consultarSalas",
                {
                    idCine: idCine
                }
            );

        const salas = respuesta.datos || [];

        selectSala.innerHTML = `
            <option value="">
                Seleccione una sala
            </option>
        `;

        salas.forEach(function (sala) {
            const opcion = document.createElement("option");

            opcion.value = sala.ID_Sala;

            opcion.textContent =
                sala.Nombre +
                " - " +
                sala.TipoPantalla +
                " (" +
                sala.Capacidad +
                " asientos)";

            if (
                idSalaSeleccionada !== null &&
                String(sala.ID_Sala) ===
                String(idSalaSeleccionada)
            ) {
                opcion.selected = true;
            }

            selectSala.appendChild(opcion);
        });

        selectSala.disabled = false;

        if (salas.length === 0) {
            selectSala.innerHTML = `
                <option value="">
                    Este cine no tiene salas registradas
                </option>
            `;

            selectSala.disabled = true;
        }
    } catch (error) {
        selectSala.innerHTML = `
            <option value="">
                Error al cargar salas
            </option>
        `;

        mostrarAlerta(error.message, "danger");
    }
}



async function cargarFunciones() {
    const cuerpoTabla =
        document.getElementById("cuerpoTablaFunciones");

    const contenedorCargando =
        document.getElementById("contenedorCargando");

    const botonActualizar =
        document.getElementById("btnActualizarTabla");

    if (!cuerpoTabla) {
        return;
    }

    if (contenedorCargando) {
        contenedorCargando.style.display = "block";
    }

    if (botonActualizar) {
        botonActualizar.disabled = true;
    }

    cuerpoTabla.innerHTML = "";

    try {
        const respuesta =
            await solicitarFuncionController(
                "consultarFunciones"
            );

        const funciones = respuesta.datos || [];

        if (funciones.length === 0) {
            cuerpoTabla.innerHTML = `
                <tr>
                    <td
                        colspan="10"
                        class="mensaje-vacio"
                    >
                        No hay funciones registradas.
                    </td>
                </tr>
            `;

            return;
        }

        funciones.forEach(function (funcion) {
            const fila = document.createElement("tr");

            fila.innerHTML = `
                <td>
                    ${escaparHtml(funcion.ID_Funcion)}
                </td>

                <td>
                    ${escaparHtml(funcion.Pelicula)}
                </td>

                <td>
                    ${escaparHtml(funcion.Cine)}
                </td>

                <td>
                    ${escaparHtml(funcion.Sala)}
                </td>

                <td>
                    ${formatearFechaHora(funcion.HoraInicio)}
                </td>

                <td>
                    ${formatearFechaHora(funcion.HoraFin)}
                </td>

                <td>
                    ₡${formatearPrecio(funcion.Precio)}
                </td>

                <td>
                    ${escaparHtml(funcion.Idioma)}
                </td>

                <td>
                    <span class="badge bg-secondary">
                        ${escaparHtml(funcion.Formato)}
                    </span>
                </td>

                <td class="acciones-tabla">

                    <button
                        type="button"
                        class="btn btn-sm btn-primary me-1"
                        data-accion="editar"
                        data-id="${escaparAtributo(
                            funcion.ID_Funcion
                        )}"
                    >
                        Editar
                    </button>

                    <button
                        type="button"
                        class="btn btn-sm btn-danger"
                        data-accion="eliminar"
                        data-id="${escaparAtributo(
                            funcion.ID_Funcion
                        )}"
                    >
                        Eliminar
                    </button>

                </td>
            `;

            cuerpoTabla.appendChild(fila);
        });

        configurarEventosTabla();

    } catch (error) {
        cuerpoTabla.innerHTML = `
            <tr>
                <td
                    colspan="10"
                    class="mensaje-vacio text-danger"
                >
                    ${escaparHtml(error.message)}
                </td>
            </tr>
        `;

        mostrarAlerta(error.message, "danger");

    } finally {
        if (contenedorCargando) {
            contenedorCargando.style.display = "none";
        }

        if (botonActualizar) {
            botonActualizar.disabled = false;
        }
    }
}



function configurarEventosTabla() {
    const botonesEditar =
        document.querySelectorAll(
            '[data-accion="editar"]'
        );

    const botonesEliminar =
        document.querySelectorAll(
            '[data-accion="eliminar"]'
        );

    botonesEditar.forEach(function (boton) {
        boton.addEventListener(
            "click",
            function () {
                cargarFuncionParaEditar(
                    this.dataset.id
                );
            }
        );
    });

    botonesEliminar.forEach(function (boton) {
        boton.addEventListener(
            "click",
            function () {
                abrirModalEliminar(
                    this.dataset.id
                );
            }
        );
    });
}


/**
 *
 * @param {SubmitEvent} evento
 */
async function procesarFormularioFuncion(evento) {
    evento.preventDefault();

    const formulario =
        document.getElementById("formFuncion");

    const botonGuardar =
        document.getElementById("btnGuardar");

    const idFuncion =
        document.getElementById("idFuncion").value;

    if (!formulario.reportValidity()) {
        return;
    }

    const datos = {
        idFuncion: idFuncion,
        idPelicula:
            document.getElementById("idPelicula").value,
        idSala:
            document.getElementById("idSala").value,
        horaInicio:
            document.getElementById("horaInicio").value,
        precio:
            document.getElementById("precio").value,
        idioma:
            document.getElementById("idioma").value,
        formato:
            document.getElementById("formato").value
    };

    const accion = idFuncion
        ? "actualizarFuncion"
        : "registrarFuncion";

    try {
        cambiarEstadoBotonGuardar(true);

        const respuesta =
            await solicitarFuncionController(
                accion,
                datos,
                "POST"
            );

        mostrarAlerta(
            respuesta.mensaje,
            "success"
        );

        limpiarFormularioFuncion();
        await cargarFunciones();

    } catch (error) {
        mostrarAlerta(
            error.message,
            "danger"
        );

    } finally {
        cambiarEstadoBotonGuardar(false);
    }
}


/**
 *
 * @param {number|string} idFuncion
 */
async function cargarFuncionParaEditar(idFuncion) {
    try {
        const respuesta =
            await solicitarFuncionController(
                "consultarFuncionPorId",
                {
                    idFuncion: idFuncion
                }
            );

        const funcion = respuesta.datos;

        if (!funcion) {
            throw new Error(
                "No fue posible obtener la función."
            );
        }

        document.getElementById("idFuncion").value =
            funcion.ID_Funcion;

        document.getElementById("idPelicula").value =
            funcion.ID_Pelicula;

        document.getElementById("idCine").value =
            funcion.ID_Cine;

        await cargarSalas(
            funcion.ID_Cine,
            funcion.ID_Sala
        );

        document.getElementById("horaInicio").value =
            convertirFechaParaInput(
                funcion.HoraInicio
            );

        document.getElementById("precio").value =
            funcion.Precio;

        document.getElementById("idioma").value =
            funcion.Idioma;

        document.getElementById("formato").value =
            funcion.Formato;

        cambiarEstadoFormulario(true);

        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });

    } catch (error) {
        mostrarAlerta(error.message, "danger");
    }
}



function limpiarFormularioFuncion() {
    const formulario =
        document.getElementById("formFuncion");

    if (formulario) {
        formulario.reset();
    }

    document.getElementById("idFuncion").value = "";

    const selectSala =
        document.getElementById("idSala");

    selectSala.innerHTML = `
        <option value="">
            Primero seleccione un cine
        </option>
    `;

    selectSala.disabled = true;

    establecerFechaMinima();
    cambiarEstadoFormulario(false);
}


/**
 *
 * @param {boolean} modoEdicion
 */
function cambiarEstadoFormulario(modoEdicion) {
    const titulo =
        document.getElementById("tituloFormulario");

    const botonGuardar =
        document.getElementById("btnGuardar");

    const botonCancelar =
        document.getElementById("btnCancelar");

    if (modoEdicion) {
        titulo.textContent = "Modificar función";
        botonGuardar.textContent = "Actualizar función";
        botonCancelar.classList.remove("d-none");
    } else {
        titulo.textContent = "Registrar función";
        botonGuardar.textContent = "Guardar función";
        botonCancelar.classList.add("d-none");
    }
}


/**
 *
 * @param {boolean} procesando
 */
function cambiarEstadoBotonGuardar(procesando) {
    const boton =
        document.getElementById("btnGuardar");

    if (!boton) {
        return;
    }

    boton.disabled = procesando;

    if (procesando) {
        boton.dataset.textoOriginal =
            boton.textContent;

        boton.innerHTML = `
            <span
                class="spinner-border
                       spinner-border-sm
                       me-2"
                role="status"
                aria-hidden="true"
            ></span>
            Procesando...
        `;
    } else {
        const idFuncion =
            document.getElementById("idFuncion").value;

        boton.textContent = idFuncion
            ? "Actualizar función"
            : "Guardar función";
    }
}


/**
 * @param {number|string} idFuncion
 */
function abrirModalEliminar(idFuncion) {
    document.getElementById(
        "idFuncionEliminar"
    ).value = idFuncion;

    const modalElemento =
        document.getElementById(
            "modalEliminarFuncion"
        );

    if (
        typeof bootstrap === "undefined" ||
        !bootstrap.Modal
    ) {
        const confirmado = window.confirm(
            "¿Está seguro de que desea eliminar la función?"
        );

        if (confirmado) {
            eliminarFuncionConfirmada();
        }

        return;
    }

    const modal =
        bootstrap.Modal.getOrCreateInstance(
            modalElemento
        );

    modal.show();
}



async function eliminarFuncionConfirmada() {
    const idFuncion =
        document.getElementById(
            "idFuncionEliminar"
        ).value;

    const boton =
        document.getElementById(
            "btnConfirmarEliminar"
        );

    if (!idFuncion) {
        mostrarAlerta(
            "No se seleccionó una función válida.",
            "danger"
        );

        return;
    }

    try {
        if (boton) {
            boton.disabled = true;
            boton.textContent = "Eliminando...";
        }

        const respuesta =
            await solicitarFuncionController(
                "eliminarFuncion",
                {
                    idFuncion: idFuncion
                },
                "POST"
            );

        cerrarModalEliminar();

        mostrarAlerta(
            respuesta.mensaje,
            "success"
        );

        const idFuncionFormulario =
            document.getElementById(
                "idFuncion"
            ).value;

        if (
            String(idFuncionFormulario) ===
            String(idFuncion)
        ) {
            limpiarFormularioFuncion();
        }

        await cargarFunciones();

    } catch (error) {
        mostrarAlerta(
            error.message,
            "danger"
        );

    } finally {
        if (boton) {
            boton.disabled = false;
            boton.textContent = "Eliminar";
        }
    }
}



function cerrarModalEliminar() {
    const modalElemento =
        document.getElementById(
            "modalEliminarFuncion"
        );

    if (
        typeof bootstrap !== "undefined" &&
        bootstrap.Modal &&
        modalElemento
    ) {
        const modal =
            bootstrap.Modal.getInstance(
                modalElemento
            );

        if (modal) {
            modal.hide();
        }
    }

    document.getElementById(
        "idFuncionEliminar"
    ).value = "";
}


/**
 *
 * @param {string} mensaje
 * @param {string} tipo
 */
function mostrarAlerta(mensaje, tipo = "info") {
    const contenedor =
        document.getElementById(
            "contenedorAlerta"
        );

    if (!contenedor) {
        return;
    }

    contenedor.innerHTML = `
        <div
            class="alert alert-${escaparAtributo(tipo)}
                   alert-dismissible fade show"
            role="alert"
        >
            ${escaparHtml(mensaje)}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Cerrar"
            ></button>
        </div>
    `;

    window.setTimeout(function () {
        const alerta =
            contenedor.querySelector(".alert");

        if (!alerta) {
            return;
        }

        if (
            typeof bootstrap !== "undefined" &&
            bootstrap.Alert
        ) {
            const instancia =
                bootstrap.Alert.getOrCreateInstance(
                    alerta
                );

            instancia.close();
        } else {
            alerta.remove();
        }
    }, 7000);
}


function establecerFechaMinima() {
    const campo =
        document.getElementById("horaInicio");

    if (!campo) {
        return;
    }

    const fecha = new Date();

    fecha.setSeconds(0, 0);

    campo.min = convertirFechaLocalParaInput(fecha);
}


/**
 *
 * @param {Date} fecha
 * @returns {string}
 */
function convertirFechaLocalParaInput(fecha) {
    const anio = fecha.getFullYear();

    const mes = String(
        fecha.getMonth() + 1
    ).padStart(2, "0");

    const dia = String(
        fecha.getDate()
    ).padStart(2, "0");

    const hora = String(
        fecha.getHours()
    ).padStart(2, "0");

    const minutos = String(
        fecha.getMinutes()
    ).padStart(2, "0");

    return (
        anio +
        "-" +
        mes +
        "-" +
        dia +
        "T" +
        hora +
        ":" +
        minutos
    );
}


/**
 *
 * @param {string} fechaMysql
 * @returns {string}
 */
function convertirFechaParaInput(fechaMysql) {
    if (!fechaMysql) {
        return "";
    }

    return String(fechaMysql)
        .replace(" ", "T")
        .substring(0, 16);
}


/**
 *
 * @param {string} fechaMysql
 * @returns {string}
 */
function formatearFechaHora(fechaMysql) {
    if (!fechaMysql) {
        return "";
    }

    const fechaNormalizada =
        String(fechaMysql).replace(" ", "T");

    const fecha = new Date(fechaNormalizada);

    if (Number.isNaN(fecha.getTime())) {
        return escaparHtml(fechaMysql);
    }

    return fecha.toLocaleString(
        "es-CR",
        {
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
            hour: "2-digit",
            minute: "2-digit"
        }
    );
}


/**
 *
 * @param {number|string} precio
 * @returns {string}
 */
function formatearPrecio(precio) {
    const numero = Number(precio);

    if (Number.isNaN(numero)) {
        return escaparHtml(precio);
    }

    return numero.toLocaleString(
        "es-CR",
        {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }
    );
}


/**
 *
 * @param {*} valor
 * @returns {string}
 */
function escaparHtml(valor) {
    const elemento =
        document.createElement("div");

    elemento.textContent =
        valor === null ||
        valor === undefined
            ? ""
            : String(valor);

    return elemento.innerHTML;
}


/**
 *
 * @param {*} valor
 * @returns {string}
 */
function escaparAtributo(valor) {
    return escaparHtml(valor)
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}