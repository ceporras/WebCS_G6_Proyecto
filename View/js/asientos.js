const cantidadEntradas = document.getElementById('cantidadEntradas');
const asientosDisponiblesSelect = document.getElementById('asientosDisponiblesSelect');
const btnAgregar = document.getElementById('btnAgregarAsiento');
const listaAsientos = document.getElementById('listaAsientos');
const hiddenSeats = document.getElementById('hiddenSeats');

//array de los asientos agregados segun cantidad de entradas
let asientosSeleccionados = [];

btnAgregar.addEventListener('click', function () {

    //definir maximo de entradas a agregar segun cantidad seleccionada
    const max = parseInt(cantidadEntradas.value);

    const id = asientosDisponiblesSelect.value;

    if (id === '') {
        alert('Seleccione un asiento.');
        return;
    }
    //aplicar maximo
    if (asientosSeleccionados.length >= max) {
        alert('Ya seleccionó el máximo de asientos.');
        return;
    }

    const asiento = asientosDisponiblesSelect.options[asientosDisponiblesSelect.selectedIndex].text;

    //agregar a array de asientosSeleccionados
    asientosSeleccionados.push({
        id: id,
        nombre: asiento
    });

    actualizarLista();

    //se remueve del dropdown para no poderlo agregar 2 veces
    asientosDisponiblesSelect.remove(asientosDisponiblesSelect.selectedIndex);
    asientosDisponiblesSelect.selectedIndex = 0;

});

//monitorear la cantidad de entradas y prevenenir que se reduzca a menos de los seleccionados
cantidadEntradas.addEventListener('change', function () {
    if (asientosSeleccionados.length > this.value) {
        alert('Reduzca primero la cantidad de asientos seleccionados.');
        this.value = asientosSeleccionados.length;
    }
});

function actualizarLista() {

    if (asientosSeleccionados.length === 0) {
        listaAsientos.innerHTML = "Ninguno";
        hiddenSeats.innerHTML = "";
        return;
    }

    let html = "<ul class='list-group'>";
    let hidden = "";

    asientosSeleccionados.forEach((asiento, index) => {

        html+=`
        <li class="list-group-item d-flex justify-content-between align-items-center w-50 rounded-pill bg-light border-0 shadow-sm mb-2 px-3 py-2">
    <span class="fw-bold text-secondary">${asiento.nombre}</span>
    <button type="button"
            class="btn-close btn-close-sm bg-danger text-white rounded-circle "
            style="--bs-btn-close-color: #fff; width: 0.5rem; height: 0.5rem;"
            onclick="eliminarAsiento(${index})"
            aria-label="Close">
    </button>
</li>`;

        hidden += `<input type="hidden"
                          name="asientos[]"
                          value="${asiento.id}">`;
    });

    html += "</ul>";

    listaAsientos.innerHTML = html;
    hiddenSeats.innerHTML = hidden;
}

function eliminarAsiento(index) {

    const asiento = asientosSeleccionados[index];

    // devolver al dropdown
    const option = new Option(asiento.nombre, asiento.id);
    asientosDisponiblesSelect.add(option);

    asientosSeleccionados.splice(index, 1);

    actualizarLista();

}