class usuario {

    constructor(objData) {
        this._objDataUsuario = objData;
    }

    listarUsuario() {
        let objData = new FormData();
        objData.append('listaUsuario', this._objDataUsuario.listarUsuario);

        fetch("controlador/UsuarioControlador.php", {
            method: 'POST',
            body: objData
        })
            .then(response => response.json())
            .catch(error => {
                console.log(error);
            })
            .then(response => {
                if (response && response['codigo'] == "200") {

                    let dataSet = [];

                    response['listaUsuario'].forEach(element => {
                        let objBotones = '<div class="btn-group" role="group">';
                        objBotones += '<button type="button" class="btn btn-warning btn_EditarUsuario" usuario="' + element.idusuario + '" nombre="' + element.nombre + '" documento="' + element.documento + '" email="' + element.email + '" telefono="' + element.telefono + '" url_foto="' + element.url_foto + '" title="Editar"><i class="bi bi-pencil"></i></button>';
                        objBotones += '<button type="button" class="btn btn-danger btn_EliminarUsuario" usuario="' + element.idusuario + '" title="Eliminar"><i class="bi bi-trash"></i></button>';
                        objBotones += '<a href="vista/modulos/carnet.php?user=' + element.idusuario + '" target="_blank" class="btn btn-primary" title="Generar Carnet"><i class="bi bi-qr-code"></i> Carnet</a>';
                        objBotones += '</div>';

                        let imgFoto = '<img src="' + element.url_foto + '" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid #ced4da; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" alt="Foto">';

                        dataSet.push([
                            imgFoto,
                            element.nombre,
                            element.documento,
                            element.email,
                            element.telefono,
                            objBotones
                        ]);
                    });

                    $("#tablaUsuarios").DataTable({
                        buttons: [
                            { extend: "colvis", text: "Columnas" },
                            "excel",
                            "pdf",
                            "print"
                        ],
                        dom: "Bfrtip",
                        responsive: false,
                        autoWidth: false,
                        destroy: true,
                        data: dataSet
                    });
                } else {
                    console.log("Error al listar usuarios");
                }
            });
    }

    registarUsuario() {
        let objData = new FormData();

        objData.append('registrarUsuario', this._objDataUsuario.registrarUsuario);
        objData.append('nombre', this._objDataUsuario.nombre);
        objData.append('documento', this._objDataUsuario.documento);
        objData.append('email', this._objDataUsuario.email);
        objData.append('telefono', this._objDataUsuario.telefono);
        objData.append('url_foto', this._objDataUsuario.url_foto);

        fetch("controlador/UsuarioControlador.php", {
            method: 'POST',
            body: objData
        })
            .then(response => response.json())
            .catch(error => {
                console.log(error);
            })
            .then(response => {
                if (response && response['codigo'] == "200") {
                    Swal.fire({
                        title: "¡Éxito!",
                        text: response['mensaje'],
                        icon: "success"
                    });

                    let form = document.getElementById('formUsuarios');
                    if (form) {
                        form.reset();
                        form.classList.remove('was-validated');
                    }

                    this.listarUsuario();
                } else {
                    Swal.fire({
                        title: "Error",
                        text: (response && response['mensaje']) ? response['mensaje'] : "No se pudo registrar el usuario",
                        icon: "error"
                    });
                }
            });
    }

    editarUsuario() {
        let objData = new FormData();

        objData.append("editarUsuario", this._objDataUsuario.editarUsuario);
        objData.append("idusuario", this._objDataUsuario.idusuario);
        objData.append("nombre", this._objDataUsuario.nombre);
        objData.append("documento", this._objDataUsuario.documento);
        objData.append("email", this._objDataUsuario.email);
        objData.append("telefono", this._objDataUsuario.telefono);
        objData.append("url_foto", this._objDataUsuario.url_foto);

        fetch("controlador/UsuarioControlador.php", {
            method: 'POST',
            body: objData
        })
            .then(response => response.json())
            .catch(error => console.log(error))
            .then(response => {
                if (response && response['codigo'] == "200") {
                    Swal.fire({
                        title: "¡Actualizado!",
                        text: response["mensaje"],
                        icon: "success"
                    });

                    $("#panelFormularioEditarUsuario").hide();
                    $("#panelPrincipalUsuarios").fadeIn(200);

                    let formEdit = document.getElementById('formEditarUsuarios');
                    if (formEdit) {
                        formEdit.reset();
                        formEdit.classList.remove('was-validated');
                    }

                    this.listarUsuario();
                } else {
                    Swal.fire({
                        title: "Error",
                        text: (response && response['mensaje']) ? response['mensaje'] : "Error al actualizar usuario",
                        icon: "error"
                    });
                }
            });
    }

    eliminarUsuario() {
        let objData = new FormData();

        objData.append("eliminarUsuario", this._objDataUsuario.eliminarUsuario);
        objData.append('idusuario', this._objDataUsuario.idusuario);

        fetch("controlador/UsuarioControlador.php", {
            method: 'POST',
            body: objData
        })
            .then(response => response.json())
            .catch(error => {
                console.log(error);
            })
            .then(response => {
                if (response && response['codigo'] == "200") {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: response['mensaje'],
                        showConfirmButton: false,
                        timer: 1500
                    });

                    this.listarUsuario();
                } else {
                    Swal.fire("Error", response['mensaje'], "error");
                }
            });
    }
}