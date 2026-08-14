$(document).on("click", ".btn-eliminarCliente", function () {
  const ID_Cliente = $(this).data("id");

  Swal.fire({
    title: "¿Eliminar usuario?",
    text: "Esta acción no se puede deshacer.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../Controller/ClienteController.php",
        type: "POST",
        data: {
          EliminarUsuario: "EliminarUsuario",
          ID_Cliente: ID_Cliente,
        },
        dataType: "json",

        success: function (response) {
          if (response.success) {
            Swal.fire({
              title: "¡Eliminado!",
              text: "El usuario fue eliminado correctamente.",
              icon: "success",
              confirmButtonText: "Aceptar",
            }).then(() => {
              location.reload();
            });
          } else {
            Swal.fire({
              title: "Error",
              text: response.message,
              icon: "error",
              confirmButtonText: "Aceptar",
            });
          }
        },

        error: function () {
          Swal.fire({
            title: "Error",
            text: "No se pudo conectar con el servidor.",
            icon: "error",
            confirmButtonText: "Aceptar",
          });
        },
      });
    }
  });
});

$(document).on("click", ".btn-editarCliente", function () {
  const usuarioId = $(this).data("id");

  $.ajax({
    url: "../Controller/ClienteController.php",
    type: "POST",

    data: {
      ObtenerCliente: "ObtenerCliente",
      ID_Cliente: usuarioId,
    },

    dataType: "json",

    success: function (response) {
      if (!response.success) {
        Swal.fire({
          title: "Error",
          text: response.message || "No se encontró el usuario.",
          icon: "error",
        });

        return;
      }

      const cliente = response.cliente;

      Swal.fire({
        title: "Editar usuario",

        html: `
                    <div class="text-start">

                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input
                                type="text"
                                id="swal-nombre"
                                class="form-control"
                                value="${cliente.Nombre || ""}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Apellido paterno</label>
                            <input
                                type="text"
                                id="swal-apellido-paterno"
                                class="form-control"
                                value="${cliente.ApellidoPaterno || ""}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Apellido materno</label>
                            <input
                                type="text"
                                id="swal-apellido-materno"
                                class="form-control"
                                value="${cliente.ApellidoMaterno || ""}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Correo</label>
                            <input
                                type="email"
                                id="swal-correo"
                                class="form-control"
                                value="${cliente.Correo || ""}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input
                                type="text"
                                id="swal-telefono"
                                class="form-control"
                                value="${cliente.Telefono || ""}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Estado</label>

                            <select id="swal-estado" class="form-select">
                                <option value="1"
                                    ${cliente.Estado == 1 ? "selected" : ""}>
                                    Activo
                                </option>

                                <option value="0"
                                    ${cliente.Estado == 0 ? "selected" : ""}>
                                    Inactivo
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rol</label>

                            <select id="swal-rol" class="form-select">

                                <option value="1"
                                    ${cliente.ID_Rol == 1 ? "selected" : ""}>
                                    Administrador
                                </option>

                                <option value="2"
                                    ${cliente.ID_Rol == 2 ? "selected" : ""}>
                                    Cliente
                                </option>

                            </select>
                        </div>

                    </div>
                `,

        showCancelButton: true,
        confirmButtonText: "Guardar cambios",
        cancelButtonText: "Cancelar",
        focusConfirm: false,

        preConfirm: function () {
          return {
            ID_Cliente: cliente.ID_Cliente,

            Nombre: document.getElementById("swal-nombre").value,

            ApellidoPaterno: document.getElementById("swal-apellido-paterno")
              .value,

            ApellidoMaterno: document.getElementById("swal-apellido-materno")
              .value,

            Correo: document.getElementById("swal-correo").value,

            Telefono: document.getElementById("swal-telefono").value,

            Estado: document.getElementById("swal-estado").value,

            ID_Rol: document.getElementById("swal-rol").value,
          };
        },
      }).then(function (result) {
        if (result.isConfirmed) {
          actualizarCliente(result.value);
        }
      });
    },

    error: function (xhr) {
      console.error(xhr.responseText);

      Swal.fire({
        title: "Error",
        text: "No se pudo obtener la información del usuario.",
        icon: "error",
      });
    },
  });
});

function actualizarCliente(cliente) {
  $.ajax({
    url: "../Controller/ClienteController.php",

    type: "POST",

    data: {
      ActualizarCliente: "ActualizarCliente",

      ID_Cliente: cliente.ID_Cliente,

      Nombre: cliente.Nombre,

      ApellidoPaterno: cliente.ApellidoPaterno,

      ApellidoMaterno: cliente.ApellidoMaterno,

      Correo: cliente.Correo,

      Telefono: cliente.Telefono,

      Estado: cliente.Estado,

      ID_Rol: cliente.ID_Rol,
    },

    dataType: "json",

    success: function (response) {
      if (response.success) {
        Swal.fire({
          title: "¡Actualizado!",
          text: "El usuario fue actualizado correctamente.",
          icon: "success",
          confirmButtonText: "Aceptar",
        }).then(function () {
          location.reload();
        });
      } else {
        Swal.fire({
          title: "Error",
          text: response.message || "No se pudo actualizar el usuario.",
          icon: "error",
        });
      }
    },

    error: function (xhr) {
      console.error("AJAX error:");
      console.error(xhr.responseText);

      Swal.fire({
        title: "Error",
        text: "Ocurrió un error al actualizar el usuario.",
        icon: "error",
      });
    },
  });
}

$(document).on("click", "#btnNuevoUsuario", function () {
  Swal.fire({
    title: "Nuevo usuario",

        html: `
            <div class="text-start">

                <div class="mb-3">
                    <label class="form-label">
                        Nombre <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        id="swal-nombre"
                        class="form-control"
                        maxlength="45"
                        placeholder="Nombre">
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Apellido paterno <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        id="swal-apellido-paterno"
                        class="form-control"
                        maxlength="45"
                        placeholder="Apellido paterno">
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Apellido materno <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        id="swal-apellido-materno"
                        class="form-control"
                        maxlength="45"
                        placeholder="Apellido materno">
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Correo <span class="text-danger">*</span>
                    </label>

                    <input
                        type="email"
                        id="swal-correo"
                        class="form-control"
                        maxlength="45"
                        placeholder="correo@ejemplo.com">
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Teléfono <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        id="swal-telefono"
                        class="form-control"
                        maxlength="45"
                        placeholder="8888-8888">
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Estado <span class="text-danger">*</span>
                    </label>

                    <select id="swal-estado" class="form-select">
                        <option value="1" selected>Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Rol <span class="text-danger">*</span>
                    </label>

                    <select id="swal-rol" class="form-select">
                        <option value="">Seleccione un rol</option>
                        <option value="1">Administrador</option>
                        <option value="2">Cliente</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Contraseña <span class="text-danger">*</span>
                    </label>

                    <input
                        type="password"
                        id="swal-password"
                        class="form-control"
                        maxlength="150"
                        placeholder="Contraseña">
                </div>

            </div>
        `,
    showCancelButton: true,
    confirmButtonText: "Crear usuario",
    cancelButtonText: "Cancelar",

        preConfirm: function () {

            const nombre = document.getElementById("swal-nombre").value.trim();
            const apellidoPaterno = document.getElementById("swal-apellido-paterno").value.trim();
            const apellidoMaterno = document.getElementById("swal-apellido-materno").value.trim();
            const correo = document.getElementById("swal-correo").value.trim();
            const telefono = document.getElementById("swal-telefono").value.trim();
            const estado = document.getElementById("swal-estado").value;
            const ID_Rol = document.getElementById("swal-rol").value;
            const password = document.getElementById("swal-password").value;


            // caracteres permitidos para nombre
            const nombreRegex = /^[A-Za-zÁÉÍÓÚáéíóúÑñÜü\s-]+$/;

            // Basic email validation
            const correoRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            const telefonoRegex = /^[0-9+\-()\s]+$/;

            if (!nombre) {
                Swal.showValidationMessage(
                    "El nombre es obligatorio."
                );
                return false;
            }

            if (!apellidoPaterno) {
                Swal.showValidationMessage(
                    "El apellido paterno es obligatorio."
                );
                return false;
            }

            if (!apellidoMaterno) {
                Swal.showValidationMessage(
                    "El apellido materno es obligatorio."
                );
                return false;
            }

            if (!correo) {
                Swal.showValidationMessage(
                    "El correo electrónico es obligatorio."
                );
                return false;
            }

            if (!telefono) {
                Swal.showValidationMessage(
                    "El teléfono es obligatorio."
                );
                return false;
            }

            if (!ID_Rol) {
                Swal.showValidationMessage(
                    "Debe seleccionar un rol."
                );
                return false;
            }

            if (!password) {
                Swal.showValidationMessage(
                    "La contraseña es obligatoria."
                );
                return false;
            }

            if (!nombreRegex.test(nombre)) {
                Swal.showValidationMessage(
                    "El nombre solo puede contener letras, espacios y guiones."
                );
                return false;
            }

            if (!nombreRegex.test(apellidoPaterno)) {
                Swal.showValidationMessage(
                    "El apellido paterno solo puede contener letras, espacios y guiones."
                );
                return false;
            }

            if (!nombreRegex.test(apellidoMaterno)) {
                Swal.showValidationMessage(
                    "El apellido materno solo puede contener letras, espacios y guiones."
                );
                return false;
            }

            if (!correoRegex.test(correo)) {
                Swal.showValidationMessage(
                    "Ingrese un correo electrónico válido."
                );
                return false;
            }

            if (!telefonoRegex.test(telefono)) {
                Swal.showValidationMessage(
                    "El teléfono contiene caracteres no válidos."
                );
                return false;
            }

            if (password.length < 6) {
                Swal.showValidationMessage(
                    "La contraseña debe tener al menos 6 caracteres."
                );
                return false;
            }

            return {

                Nombre: nombre,
                ApellidoPaterno: apellidoPaterno,
                ApellidoMaterno: apellidoMaterno,
                Correo: correo,
                Telefono: telefono,
                Estado: estado,
                ID_Rol: ID_Rol,
                Password: password
            };
        }

    }).then(function (result) {

        if (result.isConfirmed) {

            crearCliente(result.value);

        }

    });

});

function crearCliente(cliente) {
  $.ajax({
    url: "../Controller/ClienteController.php",

    type: "POST",

    data: {
      CrearCliente: "CrearCliente",

      Nombre: cliente.Nombre,
      ApellidoPaterno: cliente.ApellidoPaterno,
      ApellidoMaterno: cliente.ApellidoMaterno,
      Correo: cliente.Correo,
      Telefono: cliente.Telefono,
      Estado: cliente.Estado,
      ID_Rol: cliente.ID_Rol,
      Password: cliente.Password,
    },

    dataType: "json",

    success: function (response) {
      if (response.success) {
        Swal.fire({
          title: "¡Creado!",
          text: "El usuario fue creado correctamente.",
          icon: "success",
        }).then(function () {
          location.reload();
        });
      } else {
        Swal.fire({
          title: "Error",
          text: response.message || "No se pudo crear el usuario.",
          icon: "error",
        });
      }
    },

    error: function (xhr) {
      let mensaje = "Ocurrió un error al crear el usuario.";

      if (xhr.responseJSON && xhr.responseJSON.message) {
        mensaje = xhr.responseJSON.message;
      } else if (xhr.responseText) {
        try {
          const respuesta = JSON.parse(xhr.responseText);
          if (respuesta.message) {
            mensaje = respuesta.message;
          }
        } catch (e) {
          console.error(xhr.responseText);
        }
      }

      Swal.fire({
        title: "Error",
        text: mensaje,
        icon: "error",
      });
    },
  });
}
