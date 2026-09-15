<?php

include_once "../modelo/productoModelo.php";

class ProductoControlador {
    public $idProducto;
    public $stock;
    public $precio;
    public $descripcion;
    public $url_Foto_pro;  

    public function ctrListarProductos() {
        $objRespuesta = ProductoModelo::mdllistarProductos();
        echo json_encode($objRespuesta);
    }

    public function ctrCargarProducto(){
        $objRespuesta = ProductoModelo::mdlCargarProducto($this->idProducto);
        echo json_encode($objRespuesta);
    }

    public function ctrRegistrarProducto() {
        $objRespuesta = ProductoModelo::mdlRegistrarProducto($this->stock, $this->precio, $this->descripcion, $this->url_Foto_pro);
        echo json_encode($objRespuesta);
    }

    public function ctrEditarProducto() {
        $objRespuesta = ProductoModelo::mdlEditarProducto($this->idProducto, $this->stock, $this->precio, $this->descripcion, $this->url_Foto_pro);
        echo json_encode($objRespuesta);
    }

    public function ctrEliminarProducto() {
        $objRespuesta = ProductoModelo::mdlEliminarProducto($this->idProducto);
        echo json_encode($objRespuesta);
    }
}

if (isset($_POST["listarProductos"]) == "ok") {
    $objProducto = new ProductoControlador();
    $objProducto->ctrListarProductos();
}

if (isset($_POST["registrarProducto"]) == "ok") {
    $objProducto = new ProductoControlador();
    $objProducto->stock = $_POST["stock"];
    $objProducto->precio = $_POST["precio"];
    $objProducto->descripcion = $_POST["descripcion"];
    $objProducto->url_Foto_pro = $_FILES["foto"];
    $objProducto->ctrRegistrarProducto();
}

    if (isset($_POST["editarProducto"]) && $_POST["editarProducto"] == "ok") {
        $objProducto = new ProductoControlador();
        $objProducto->idProducto = $_POST["idProducto"];
        $objProducto->stock = $_POST["stock"];
        $objProducto->precio = $_POST["precio"];
        $objProducto->descripcion = $_POST["descripcion"];
        $objProducto->url_Foto_pro = isset($_FILES["foto"]) ? $_FILES["foto"] : null;
        $objProducto->ctrEditarProducto();
    }

    if (isset($_POST["eliminarProducto"]) && $_POST["eliminarProducto"] == "ok") {
        $objProducto = new ProductoControlador();
        $objProducto->idProducto = $_POST["idProducto"];
        $objProducto->ctrEliminarProducto();
    }