(function () {
    'use strict';

    let cargarProductos = () => {
        let objProducto = new Producto({ "listarProductos": "ok" });
        objProducto.listarProductos();
    };
    cargarProductos();

    $("#panelFormularioEditarProductos").hide();

    function volverAlPanelPrincipal() {
        $("#panelFormularioEditarProductos").hide();
        $("#panelPrincipalProductos").fadeIn(200);
        let formEdit = document.getElementById("formEditarProductos");
        if (formEdit) {
            formEdit.reset();
            formEdit.classList.remove('was-validated');
        }
    }

    let btnRegresarEditarProducto = document.getElementById('btn-RegresarEditar');
    if (btnRegresarEditarProducto) {
        btnRegresarEditarProducto.addEventListener("click", volverAlPanelPrincipal);
    }

    let btnCancelarEditarProducto = document.getElementById('btn_CancelarEditarProducto');
    if (btnCancelarEditarProducto) {
        btnCancelarEditarProducto.addEventListener("click", volverAlPanelPrincipal);
    }

    $("#tablaProductos").on("click", "#btn-eliminar", function () {
        Swal.fire({
            title: "¿Está usted seguro?",
            text: "Si confirma esta opción no podrá recuperar el registro ni su contenido asociado.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                let idProducto = $(this).attr("idProducto");
                let objData = { "eliminarProducto": "ok", "idProducto": idProducto };
                let objProducto = new Producto(objData);
                objProducto.eliminarProducto();
            }
        });
    });

    $("#tablaProductos").on("click", "#btn-editar", function () {
        $("#panelPrincipalProductos").hide();
        $("#panelFormularioEditarProductos").fadeIn(200);

        let idProducto = $(this).attr("idProducto");
        let stock = $(this).attr("stock");
        let precio = $(this).attr("precio");
        let descripcion = $(this).attr("descripcion");
        let urlFoto = $(this).attr("urlFoto");

        if (!$("#idProducto").length) {
            $("<input>").attr({
                type: 'hidden',
                id: 'idProducto',
                value: idProducto
            }).appendTo("#formEditarProductos");
        } else {
            $("#idProducto").val(idProducto);
        }

        $("#txt_edit_stock").val(stock);
        $("#txt_edit_precio").val(precio);
        $("#txt_edit_descripcion").val(descripcion);
        $("#img_edit_url_Foto_pro").attr("src", urlFoto);
        $("#txt_edit_url_Foto_pro").val("");
    });

    const formsEditar = document.querySelectorAll('#formEditarProductos');

    Array.from(formsEditar).forEach(form => {
        form.addEventListener('submit', event => {
            event.preventDefault();

            if (!form.checkValidity()) {
                event.stopPropagation();
                form.classList.add('was-validated');
            } else {
                let idProducto = $("#idProducto").val();
                let stock = $("#txt_edit_stock").val();
                let precio = $("#txt_edit_precio").val();
                let descripcion = $("#txt_edit_descripcion").val();
                let nuevaFoto = $("#txt_edit_url_Foto_pro")[0].files[0];

                let objData = {
                    "editarProducto": "ok",
                    "idProducto": idProducto,
                    "stock": stock,
                    "precio": precio,
                    "descripcion": descripcion
                };

                if (nuevaFoto) {
                    objData["fotoNueva"] = nuevaFoto;
                }

                let objProducto = new Producto(objData);
                objProducto.editarProducto();

                volverAlPanelPrincipal();
            }
        }, false);
    });

    var formProducto = document.querySelectorAll('#formProducto');
    Array.prototype.slice.call(formProducto).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            if (!form.checkValidity()) {
                event.stopPropagation();
                form.classList.add('was-validated');
            } else {
                let stock = document.getElementById('txt-stock').value;
                let precio = document.getElementById('txt-precio').value;
                let descripcion = document.getElementById('txt-descripcion').value;
                let foto = document.getElementById('txt-url-foto').files[0];

                let objData = {
                    "registrarProducto": "ok",
                    "stock": stock,
                    "precio": precio,
                    "descripcion": descripcion,
                    "foto": foto,
                    "listarProductos": "ok"
                };

                let objProducto = new Producto(objData);
                objProducto.registrarProducto();
            }
        });
    });
})();