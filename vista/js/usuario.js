(function () {
    'use strict';

    listaUsuarios();

    function listaUsuarios() {
        let objData = { "listarUsuario": "ok" };
        let objUsuarios = new usuario(objData);
        objUsuarios.listarUsuario();
    }

    function validarFormatoEmail(email) {
        let regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return regex.test(email);
    }

    let inputEmailRegistro = document.getElementById('txt-email');
    if (inputEmailRegistro) {
        inputEmailRegistro.addEventListener('input', function () {
            if (this.value.trim() === '' || !validarFormatoEmail(this.value.trim())) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    }

    let inputEmailEditar = document.getElementById('txt_edit_email');
    if (inputEmailEditar) {
        inputEmailEditar.addEventListener('input', function () {
            if (this.value.trim() === '' || !validarFormatoEmail(this.value.trim())) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    }

    let formUsuarios = document.querySelectorAll('#formUsuarios');

    Array.prototype.slice.call(formUsuarios)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                let email = document.getElementById('txt-email').value.trim();

                if (!validarFormatoEmail(email)) {
                    document.getElementById('txt-email').classList.add('is-invalid');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Correo inválido',
                        text: 'El formato del correo electrónico es incorrecto. Ingrese un email válido (ej: usuario@correo.com).'
                    });
                    return;
                }

                if (!form.checkValidity()) {
                    event.stopPropagation();
                    form.classList.add('was-validated');
                } else {
                    let nombre = document.getElementById('txt-nombre').value;
                    let documento = document.getElementById('txt-documento').value;
                    let telefono = document.getElementById('txt-telefono').value;
                    let url_foto = document.getElementById('txt-url_foto').files[0];

                    let objData = {
                        'registrarUsuario': 'ok',
                        'nombre': nombre,
                        'documento': documento,
                        'email': email,
                        'telefono': telefono,
                        'url_foto': url_foto
                    };

                    let objUsuario = new usuario(objData);
                    objUsuario.registarUsuario();
                }
            });
        });

    $("#tablaUsuarios").on("click", ".btn_EliminarUsuario", function () {
        Swal.fire({
            title: "¿Está usted seguro?",
            text: "Si confirma esta opción no podrá recuperar el registro.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                let idusuario = $(this).attr("usuario");
                let objData = { "eliminarUsuario": "ok", "idusuario": idusuario };
                let objUsuario = new usuario(objData);
                objUsuario.eliminarUsuario();
            }
        });
    });

    $("#tablaUsuarios").on("click", ".btn_EditarUsuario", function () {
        $("#panelPrincipalUsuarios").hide();
        $("#panelFormularioEditarUsuario").fadeIn(200);

        let id = $(this).attr("usuario");
        let nombre = $(this).attr("nombre");
        let documento = $(this).attr("documento");
        let email = $(this).attr("email");
        let telefono = $(this).attr("telefono");
        let url_foto = $(this).attr("url_foto");

        $("#txt_edit_idusuario").val(id);
        $("#txt_edit_nombre").val(nombre);
        $("#txt_edit_documento").val(documento);
        $("#txt_edit_email").val(email);
        $("#txt_edit_telefono").val(telefono);
        $("#img_edit_preview").attr("src", url_foto);
        $("#txt_edit_url_foto").val("");

        let editEmailInput = document.getElementById('txt_edit_email');
        if (editEmailInput) {
            editEmailInput.classList.remove('is-invalid');
            editEmailInput.classList.remove('is-valid');
        }
    });

    function volverAlPanelPrincipal() {
        $("#panelFormularioEditarUsuario").hide();
        $("#panelPrincipalUsuarios").fadeIn(200);
        let formEdit = document.getElementById("formEditarUsuarios");
        if (formEdit) {
            formEdit.reset();
            formEdit.classList.remove('was-validated');
        }
    }

    let btnRegresar = document.getElementById("btn_RegresarEditar");
    if (btnRegresar) {
        btnRegresar.addEventListener("click", volverAlPanelPrincipal);
    }

    let btnCancelar = document.getElementById("btn_CancelarEditar");
    if (btnCancelar) {
        btnCancelar.addEventListener("click", volverAlPanelPrincipal);
    }

    const formsEditar = document.querySelectorAll("#formEditarUsuarios");

    Array.from(formsEditar).forEach(form => {
        form.addEventListener('submit', event => {
            event.preventDefault();

            let email = document.getElementById("txt_edit_email").value.trim();

            if (!validarFormatoEmail(email)) {
                document.getElementById('txt_edit_email').classList.add('is-invalid');
                Swal.fire({
                    icon: 'warning',
                    title: 'Correo inválido',
                    text: 'El formato del correo electrónico es incorrecto. Ingrese un email válido (ej: usuario@correo.com).'
                });
                return;
            }

            if (!form.checkValidity()) {
                event.stopPropagation();
                form.classList.add('was-validated');
            } else {
                let idusuario = document.getElementById("txt_edit_idusuario").value;
                let nombre = document.getElementById("txt_edit_nombre").value;
                let documento = document.getElementById("txt_edit_documento").value;
                let telefono = document.getElementById("txt_edit_telefono").value;
                let url_foto = document.getElementById("txt_edit_url_foto").files[0];

                let objData = {
                    "editarUsuario": "ok",
                    "idusuario": idusuario,
                    "nombre": nombre,
                    "documento": documento,
                    "email": email,
                    "telefono": telefono,
                    "url_foto": url_foto
                };

                let objUsuario = new usuario(objData);
                objUsuario.editarUsuario();
            }
        }, false);
    });
})();