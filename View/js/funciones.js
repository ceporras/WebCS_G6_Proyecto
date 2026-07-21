"use strict";

const URL_FUNCION_CONTROLLER =
    "/WebCS_G6_Proyecto/Controller/FuncionController.php";

const $ = (id) => document.getElementById(id);

document.addEventListener("DOMContentLoaded", async () => {
    configurarEventos();
    establecerFechaMinima();
    cambiarModoEdicion(false);

    await Promise.all([
        cargarSelect("consultarPeliculas", "idPelicula", "Seleccione una película", pelicula =>
            `${pelicula.Titulo} (${pelicula.Duracion} min)`,
            "ID_Pelicula"
        ),
        cargarSelect("consultarCines", "idCine", "Seleccione un cine", cine =>
            `${cine.Nombre}${cine.Ciudad ? " - " + cine.Ciudad : ""}`,
            "ID_Cine"
        )
    ]);

    await cargarFunciones();
});

function configurarEventos() {
    $("formFuncion")?.addEventListener("submit", guardarFuncion);

    $("idCine")?.addEventListener("change", event => {
        cargarSalas(event.target.value);
    });

    $("btnCancelar")?.addEventListener("click", limpiarFormulario);
    $("btnActualizarTabla")?.addEventListener("click", cargarFunciones);
    $("btnConfirmarEliminar")?.addEventListener("click", eliminarFuncion);

    $("cuerpoTablaFunciones")?.addEventListener("click", event => {
        const boton = event.target.closest("[data-accion]");

        if (!boton) return;

        if (boton.dataset.accion === "editar") {
            cargarFuncionParaEditar(boton.dataset.id);
        }

        if (boton.dataset.accion === "eliminar") {
            abrirModalEliminar(boton.dataset.id);
        }
    });
}

async function solicitar(accion, datos = {}, metodo = "GET") {
    let url = URL_FUNCION_CONTROLLER;
    const opciones = { method: metodo };

    if (metodo === "GET") {
        const parametros = new URLSearchParams({ accion });

        Object.entries(datos).forEach(([clave, valor]) => {
            if (valor !== "" && valor !== null && valor !== undefined) {
                parametros.append(clave, valor);
            }
        });

        url += "?" + parametros.toString();
    } else {
        const formulario = new FormData();
        formulario.append("accion", accion);

        Object.entries(datos).forEach(([clave, valor]) => {
            formulario.append(clave, valor ?? "");
        });

        opciones.body = formulario;
    }

    const respuesta = await fetch(url, opciones);
    const contenido = await respuesta.json();

    if (!respuesta.ok || !contenido.exito) {
        throw new Error(contenido.mensaje || "No fue posible completar la operación.");
    }

    return contenido;
}

async function cargarSelect(accion, idSelect, textoInicial, crearTexto, campoId, datos = {}) {
    const select = $(idSelect);

    if (!select) return;

    select.disabled = true;
    select.innerHTML = '<option value="">Cargando...</option>';

    try {
        const respuesta = await solicitar(accion, datos);
        const registros = respuesta.datos || [];

        select.innerHTML = `<option value="">${textoInicial}</option>`;

        registros.forEach(registro => {
            const opcion = document.createElement("option");
            opcion.value = registro[campoId];
            opcion.textContent = crearTexto(registro);
            select.appendChild(opcion);
        });

        select.disabled = registros.length === 0;

        if (registros.length === 0) {
            select.innerHTML = '<option value="">No hay registros disponibles</option>';
        }

        return registros;
    } catch (error) {
        select.innerHTML = '<option value="">Error al cargar</option>';
        mostrarAlerta(error.message, "danger");
        return [];
    }
}

async function cargarSalas(idCine, idSalaSeleccionada = "") {
    const select = $("idSala");

    if (!select) return;

    if (!idCine) {
        select.disabled = true;
        select.innerHTML = '<option value="">Primero seleccione un cine</option>';
        return;
    }

    await cargarSelect(
        "consultarSalas",
        "idSala",
        "Seleccione una sala",
        sala => `${sala.Nombre} - ${sala.TipoPantalla} (${sala.Capacidad} asientos)`,
        "ID_Sala",
        { idCine }
    );

    select.value = idSalaSeleccionada;
}

async function cargarFunciones() {
    const tabla = $("cuerpoTablaFunciones");
    const cargando = $("contenedorCargando");
    const boton = $("btnActualizarTabla");

    if (!tabla) return;

    cargando && (cargando.style.display = "block");
    boton && (boton.disabled = true);

    try {
        const respuesta = await solicitar("consultarFunciones");
        const funciones = respuesta.datos || [];

        if (funciones.length === 0) {
            tabla.innerHTML = `
                <tr>
                    <td colspan="10" class="mensaje-vacio">
                        No hay funciones registradas.
                    </td>
                </tr>`;
            return;
        }

        tabla.innerHTML = funciones.map(funcion => `
            <tr>
                <td>${escapar(funcion.ID_Funcion)}</td>
                <td>${escapar(funcion.Pelicula)}</td>
                <td>${escapar(funcion.Cine)}</td>
                <td>${escapar(funcion.Sala)}</td>
                <td>${formatearFecha(funcion.HoraInicio)}</td>
                <td>${formatearFecha(funcion.HoraFin)}</td>
                <td>₡${formatearPrecio(funcion.Precio)}</td>
                <td>${escapar(funcion.Idioma)}</td>
                <td>
                    <span class="badge bg-secondary">
                        ${escapar(funcion.Formato)}
                    </span>
                </td>
                <td class="acciones-tabla">
                    <button
                        type="button"
                        class="btn btn-sm btn-primary me-1"
                        data-accion="editar"
                        data-id="${escapar(funcion.ID_Funcion)}">
                        Editar
                    </button>

                    <button
                        type="button"
                        class="btn btn-sm btn-danger"
                        data-accion="eliminar"
                        data-id="${escapar(funcion.ID_Funcion)}">
                        Eliminar
                    </button>
                </td>
            </tr>
        `).join("");
    } catch (error) {
        tabla.innerHTML = `
            <tr>
                <td colspan="10" class="mensaje-vacio text-danger">
                    ${escapar(error.message)}
                </td>
            </tr>`;
        mostrarAlerta(error.message, "danger");
    } finally {
        cargando && (cargando.style.display = "none");
        boton && (boton.disabled = false);
    }
}

async function guardarFuncion(event) {
    event.preventDefault();

    const formulario = $("formFuncion");

    if (!formulario.reportValidity()) return;

    const idFuncion = $("idFuncion").value;

    const datos = {
        idFuncion,
        idPelicula: $("idPelicula").value,
        idSala: $("idSala").value,
        horaInicio: $("horaInicio").value,
        precio: $("precio").value,
        idioma: $("idioma").value,
        formato: $("formato").value
    };

    const accion = idFuncion ? "actualizarFuncion" : "registrarFuncion";

    try {
        cambiarEstadoBoton(true);

        const respuesta = await solicitar(accion, datos, "POST");

        mostrarAlerta(respuesta.mensaje, "success");
        limpiarFormulario();
        await cargarFunciones();
    } catch (error) {
        mostrarAlerta(error.message, "danger");
    } finally {
        cambiarEstadoBoton(false);
    }
}

async function cargarFuncionParaEditar(idFuncion) {
    try {
        const respuesta = await solicitar("consultarFuncionPorId", { idFuncion });
        const funcion = respuesta.datos;

        $("idFuncion").value = funcion.ID_Funcion;
        $("idPelicula").value = funcion.ID_Pelicula;
        $("idCine").value = funcion.ID_Cine;

        await cargarSalas(funcion.ID_Cine, funcion.ID_Sala);

        $("horaInicio").value = convertirFechaInput(funcion.HoraInicio);
        $("precio").value = funcion.Precio;
        $("idioma").value = funcion.Idioma;
        $("formato").value = funcion.Formato;

        cambiarModoEdicion(true);
        window.scrollTo({ top: 0, behavior: "smooth" });
    } catch (error) {
        mostrarAlerta(error.message, "danger");
    }
}

function limpiarFormulario() {
    $("formFuncion")?.reset();
    $("idFuncion").value = "";

    const selectSala = $("idSala");

    if (selectSala) {
        selectSala.disabled = true;
        selectSala.innerHTML =
            '<option value="">Primero seleccione un cine</option>';
    }

    establecerFechaMinima();
    cambiarModoEdicion(false);
}

function cambiarModoEdicion(editando) {
    $("tituloFormulario").textContent =
        editando ? "Modificar función" : "Registrar función";

    $("btnGuardar").textContent =
        editando ? "Actualizar función" : "Guardar función";

    $("btnCancelar").classList.toggle("d-none", !editando);
}

function cambiarEstadoBoton(procesando) {
    const boton = $("btnGuardar");

    if (!boton) return;

    boton.disabled = procesando;

    if (procesando) {
        boton.textContent = "Procesando...";
    } else {
        boton.textContent = $("idFuncion").value
            ? "Actualizar función"
            : "Guardar función";
    }
}

function abrirModalEliminar(idFuncion) {
    $("idFuncionEliminar").value = idFuncion;
    const modal = $("modalEliminarFuncion");

    if (typeof bootstrap !== "undefined" && bootstrap.Modal && modal) {
        bootstrap.Modal.getOrCreateInstance(modal).show();
        return;
    }

    if (confirm("¿Está seguro de que desea eliminar la función?")) {
        eliminarFuncion();
    }
}

async function eliminarFuncion() {
    const idFuncion = $("idFuncionEliminar").value;

    if (!idFuncion) return;

    try {
        const respuesta = await solicitar(
            "eliminarFuncion",
            { idFuncion },
            "POST"
        );

        cerrarModalEliminar();
        mostrarAlerta(respuesta.mensaje, "success");

        if ($("idFuncion").value === idFuncion) {
            limpiarFormulario();
        }

        await cargarFunciones();
    } catch (error) {
        mostrarAlerta(error.message, "danger");
    }
}

function cerrarModalEliminar() {
    const modal = $("modalEliminarFuncion");

    if (typeof bootstrap !== "undefined" && bootstrap.Modal && modal) {
        bootstrap.Modal.getInstance(modal)?.hide();
    }

    $("idFuncionEliminar").value = "";
}

function mostrarAlerta(mensaje, tipo = "info") {
    const contenedor = $("contenedorAlerta");

    if (!contenedor) {
        alert(mensaje);
        return;
    }

    contenedor.innerHTML = `
        <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            ${escapar(mensaje)}
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Cerrar">
            </button>
        </div>`;

    setTimeout(() => {
        contenedor.querySelector(".alert")?.remove();
    }, 5000);
}

function establecerFechaMinima() {
    const campo = $("horaInicio");

    if (!campo) return;

    const fecha = new Date();
    fecha.setSeconds(0, 0);

    campo.min =
        fecha.getFullYear() + "-" +
        String(fecha.getMonth() + 1).padStart(2, "0") + "-" +
        String(fecha.getDate()).padStart(2, "0") + "T" +
        String(fecha.getHours()).padStart(2, "0") + ":" +
        String(fecha.getMinutes()).padStart(2, "0");
}

function convertirFechaInput(fecha) {
    return fecha ? String(fecha).replace(" ", "T").substring(0, 16) : "";
}

function formatearFecha(fecha) {
    if (!fecha) return "";

    const valor = new Date(String(fecha).replace(" ", "T"));

    return isNaN(valor)
        ? escapar(fecha)
        : valor.toLocaleString("es-CR", {
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
            hour: "2-digit",
            minute: "2-digit"
        });
}

function formatearPrecio(precio) {
    return Number(precio).toLocaleString("es-CR", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function escapar(valor) {
    const elemento = document.createElement("div");
    elemento.textContent = valor ?? "";
    return elemento.innerHTML;
}