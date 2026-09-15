class Producto {
    constructor(objData) {
        this._objDataProducto = objData;
    }

    listarProductos() {
        let objData = new FormData();
        objData.append("listarProductos", "ok")
        fetch("controlador/productoControlador.php", {
            method: 'POST',
            body: objData
        })
        .then(response => response.json())
        .then(response => {
            let dataSet = [];
            if (response["codigo"] === "200") {
                response["listarProductos"].forEach(item => {
                    let objBotones = '<div class="btn-group" role="group" aria-label="Basic example">';
                    objBotones += '<button id="btn-editar" type="button" class="btn btn-info" idProducto="'+item.idProducto+'" stock="'+item.stock+'" precio="'+item.precio+'" descripcion="'+item.descripcion+'" urlFoto="'+item.url_Foto_pro+'"><i class="bi bi-pen">Editar</i></button>';
                    objBotones += '<button id="btn-eliminar" type="button" class="btn btn-danger" idProducto="'+item.idProducto+'"><i class="bi bi-x">Eliminar</i></button>';
                    objBotones += '<a href="vista/modulos/codigoBarras.php?idProducto=' + item.idProducto + '" target="_blank" id="btnCodigo" type="button" class="btn btn-primary">Código de barras</a>';
                    objBotones += '</div>';
    
                    let imagen = '<img src="'+item.url_Foto_pro+'" alt="" class="mc-imagen" style="width: 90px; height: 80px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">';
    
                    dataSet.push([imagen, item.stock, item.precio, item.descripcion, objBotones]);
                });
    
                $("#tablaProductos").DataTable({
                    destroy: true,
                    responsive: true,
                    data: dataSet,
                });
            }
        });
    }

    registrarProducto() {
        let objData = new FormData();
        objData.append("registrarProducto", this._objDataProducto.registrarProducto);
        objData.append("stock", this._objDataProducto.stock);
        objData.append("precio", this._objDataProducto.precio);
        objData.append("descripcion", this._objDataProducto.descripcion);
        objData.append("foto", this._objDataProducto.foto);

        fetch("controlador/productoControlador.php", {
            method: 'POST',
            body: objData
        })
        .then(response => response.json())
        .catch(error => {
            console.log(error);
        })
        .then(response => {
            if (response.codigo == "200") {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: response["mensaje"],
                    showConfirmButton: false,
                    timer: 1500
                });
                this.listarProductos();
                document.getElementById('formProducto').reset();
            } else {
                Swal.fire(response["mensaje"]);
            }
        });
    }

    editarProducto() {
        let objData = new FormData();
        objData.append("editarProducto", "ok");
        objData.append("idProducto", this._objDataProducto.idProducto);
        objData.append("stock", this._objDataProducto.stock);
        objData.append("precio", this._objDataProducto.precio);
        objData.append("descripcion", this._objDataProducto.descripcion);

        if (this._objDataProducto.fotoNueva) {
            objData.append("foto", this._objDataProducto.fotoNueva);
        }

        fetch("controlador/productoControlador.php", {
            method: 'POST',
            body: objData
        })
        .then(response => response.json())
        .catch(error => {
            console.log(error);
        })
        .then(response => {
            if (response.codigo === "200") {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: response["mensaje"],
                    showConfirmButton: false,
                    timer: 1500
                });
                this.listarProductos();
            } else {
                Swal.fire(response["mensaje"]);
            }
        });
    }

    eliminarProducto() {
        let objData = new FormData();
        objData.append("eliminarProducto", this._objDataProducto.eliminarProducto);
        objData.append('idProducto', this._objDataProducto.idProducto);
    
        fetch("controlador/productoControlador.php", {
            method: 'POST',
            body: objData
        })
            .then(response => response.json())
            .catch(error => {
                console.log(error); 
            })
            .then(response => {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: response['mensaje'],
                    showConfirmButton: false,
                    timer: 1500
                });
                this.listarProductos();
            });
    }
    
}  