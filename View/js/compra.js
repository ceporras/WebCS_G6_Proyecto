$(function () {
  $("#formPago").validate({
    rules: {
      nombreCompleto: {
        required: true,
        minlength: 5
      },
      direccion: {
        required: true,
        minlength: 10
      },
      email: {
        required: true,
        email: true
      },
      nombreTarjeta: {
        required: true,
        minlength: 5
      },
      numeroTarjeta: {
        required: true,
        minlength: 19,
        cardNumber: true
      },
      expiracion: {
        required: true,
        expiry: true
      },
      cvv: {
        required: true,
        minlength: 3,
        maxlength: 4
      },
    },
    messages: {
      nombreCompleto: {
        required: "Campo obligatorio.",
        minlength: "Mínimo 5 caracteres."
      },
      direccion: {
        required: "Campo obligatorio.",
        minlength: "Mínimo 10 caracteres."
      },
      correoElectronico: {
        required: "Campo obligatorio.",
        email: "Formato no válido."
      },
      nombreTarjeta: {
        required: "Campo obligatorio.",
        minlength: "Mínimo 5 caracteres."
      },
      numeroTarjeta: {
        required: "Campo obligatorio.",
        minlength: "Ingrese un número válido (0000 0000 0000 0000).",
        pattern: "Ingrese un número válido (0000 0000 0000 0000)."
      },
      expiracion: {
        required: "Campo obligatorio.",
        expiry: "Fecha inválida o vencida."
      },
      cvv: {
        required: "Campo obligatorio.",
        minlength: "Mínimo 3 caracteres.",
        maxlength: "Máximo 4 caracteres."
      },
    },
    errorElement: "div",
    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".mb-3").append(error);
    },
    highlight: function (element) {
      $(element).addClass("is-invalid").removeClass("is-valid");
    },
    unhighlight: function (element) {
      $(element).addClass("is-valid").removeClass("is-invalid");
    },
  });
});

$("#numeroTarjeta").on("input", function () {
    let value = $(this).val().replace(/\D/g, "");
    value = value.substring(0,16);
    value = value.replace(/(.{4})/g, "$1 ").trim();
    $(this).val(value);
});

$("#expiracion").on("input", function () {

    let value = $(this).val().replace(/\D/g,"");

    if(value.length > 4)
        value = value.substring(0,4);

    if(value.length >= 3)
        value = value.substring(0,2) + "/" + value.substring(2);

    $(this).val(value);

});

$("#cvv").on("input", function () {
    $(this).val($(this).val().replace(/\D/g,"").substring(0,4));
});

$.validator.addMethod("expiry", function(value) {

    if(!/^(0[1-9]|1[0-2])\/([0-9]{2})$/.test(value))
        return false;

    const parts = value.split("/");
    const month = parseInt(parts[0],10);
    const year = 2000 + parseInt(parts[1],10);

    const today = new Date();

    const exp = new Date(year, month);

    return exp > today;

});

$.validator.addMethod("cardNumber", function(value, element) {
    return this.optional(element) ||
           /^[0-9]{4}( [0-9]{4}){3}$/.test(value);
}, "Ingrese un número de tarjeta válido.");