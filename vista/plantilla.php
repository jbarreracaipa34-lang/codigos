<?php

include_once "vista/modulos/cabecera.php";

include_once "vista/modulos/menu.php";

$listaRutas = array("usuarios","productos");

if (isset($_GET["ruta"]) && in_array($_GET["ruta"],$listaRutas)){
    include_once "vista/modulos/".$_GET["ruta"].".php";
}else{
    include_once "vista/modulos/usuarios.php";
}

include_once "vista/modulos/pie.php";