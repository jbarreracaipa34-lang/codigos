<?php

include_once "../modelo/UsuarioModelo.php";

class UsuarioControlador{

    public $idusuario;
    public $nombre;
    public $documento;
    public $email;
    public $telefono;
    public $url_foto;

    public function ctrListarUsuario(){
        $objRespuesta = UsuarioModelo::mdlListarUsuario();
        echo json_encode($objRespuesta);
    }

    public function ctrRegistarUsuario(){
        $objRespuesta = UsuarioModelo::mdlRegistrarUsuario($this->nombre, $this->documento, $this->email, $this->telefono, $this->url_foto);
        echo json_encode($objRespuesta);
    }

    public function ctrEditarUsuario() {
        $objRespuesta = UsuarioModelo::mdlEditarUsuario($this->idusuario, $this->nombre, $this->documento, $this->email, $this->telefono, $this->url_foto);
        echo json_encode($objRespuesta);
    }

    public function ctrEliminarUsuario(){
        $objRespuesta = UsuarioModelo::mdlEliminarUsuario($this->idusuario);
        echo json_encode($objRespuesta);
    }

    public function ctrCargarUsuario(){
        $objRespuesta = UsuarioModelo::mdlCargarUsuario($this->idusuario);
        echo json_encode($objRespuesta);
    }
}

if(isset($_POST['listaUsuario']) == 'ok') {
    $objUsuario = new UsuarioControlador();
    $objUsuario->ctrListarUsuario();
}

if(isset($_POST['registrarUsuario']) == 'ok') {
    $objUsuario = new UsuarioControlador();
    $objUsuario->nombre = $_POST['nombre'];
    $objUsuario->documento = $_POST['documento'];
    $objUsuario->email = $_POST['email'];
    $objUsuario->telefono = $_POST['telefono'];
    $objUsuario->url_foto = $_FILES['url_foto'];
    $objUsuario->ctrRegistarUsuario();
}

if (isset($_POST['eliminarUsuario']) == 'ok') {
    $objUsuario = new UsuarioControlador();
    $objUsuario->idusuario = $_POST['idusuario'];
    $objUsuario->ctrEliminarUsuario();
}

if (isset($_POST['editarUsuario']) == 'ok') {
    $objUsuario = new UsuarioControlador();
    $objUsuario->idusuario = $_POST['idusuario'];
    $objUsuario->nombre = $_POST['nombre'];
    $objUsuario->documento = $_POST['documento'];
    $objUsuario->email = $_POST['email'];
    $objUsuario->telefono = $_POST['telefono'];
    $objUsuario->url_foto = $_FILES['url_foto'];
    $objUsuario->ctrEditarUsuario();
}
