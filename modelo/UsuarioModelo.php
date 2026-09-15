<?php


include_once "conexion.php";
include_once "FolderModelo.php";

class UsuarioModelo{

        public static function mdlListarUsuario(){
            $mensaje = array();

            try {
                $objRespuesta = Conexion::conectar()->prepare("SELECT * FROM usuario");
                $objRespuesta->execute();
                $listaUsuario = $objRespuesta->fetchAll();
                $objRespuesta = null;

                $mensaje = array("codigo" => "200", "listaUsuario" => $listaUsuario);

            } catch (Exception $e) {

                $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
            }

            return $mensaje;
        }

    public static function mdlRegistrarUsuario( $nombre,$documento,$email,$telefono, $url_foto){
        $mensaje = FolderModelo::mdlCrearFolder($documento);

        if ($mensaje["codigo"] == "200") {

            $ruta = $mensaje["ruta"];
            $arrayArchivo = explode('.', $url_foto["name"]);

            $nombreArchivo = uniqid('img-') . '.' . end($arrayArchivo);
            $rutaFinal = $ruta . $nombreArchivo;

            if (move_uploaded_file($url_foto['tmp_name'], '../' . $ruta . $nombreArchivo)) {
                try {
                    $objRespuesta = Conexion::conectar()->prepare("INSERT INTO usuario (nombre, documento, email, telefono, url_foto) VALUES (:nombre,:documento,:email, :telefono, :url_foto)");
                    $objRespuesta->bindParam(':nombre', $nombre);
                    $objRespuesta->bindParam(':documento', $documento);
                    $objRespuesta->bindParam(':email', $email);
                    $objRespuesta->bindParam(':telefono', $telefono);
                    $objRespuesta->bindParam(':url_foto', $rutaFinal);
                    if ($objRespuesta->execute()) {
                        $mensaje = array("codigo" => "200", "mensaje" => "Usuario registrado correctamente");
                    } else {
                        $mensaje = array("codigo" => "401", "mensaje" => "Error al registrar el usuario");
                    }
                } catch (Exception $e) {
                    $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
                }
            }
        }

        return $mensaje;
    }
        public static function mdlEliminarUsuario($idusuario){
            $mensaje = array();

            try {
                $objRespuesta = Conexion::conectar()->prepare("SELECT url_foto FROM usuario WHERE idusuario = :idusuario");
                $objRespuesta->bindParam(':idusuario', $idusuario);
                $objRespuesta->execute();
                $usuario = $objRespuesta->fetch();

                if ($usuario) {
                    $folder = dirname($usuario['url_foto']);
                    FolderModelo::mdlEliminarFolder($folder);

                    $objRespuesta = Conexion::conectar()->prepare("DELETE FROM usuario WHERE idusuario = :idusuario");
                    $objRespuesta->bindParam(':idusuario', $idusuario);

                    if ($objRespuesta->execute()) {
                        $mensaje = array("codigo" => "200", "mensaje" => "Usuario eliminado correctamente");
                    } else {
                        $mensaje = array("codigo" => "401", "mensaje" => "Error al eliminar el usuario");
                    }
                } else {
                    $mensaje = array("codigo" => "404", "mensaje" => "Usuario no encontrado");
                }
            } catch (Exception $e) {
                $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
            }

            return $mensaje;
        }

        public static function mdlEditarUsuario($idusuario,$nombre, $documento, $email, $telefono, $url_foto){
            $mensaje = array();

            try {
                $objRespuesta = Conexion::conectar()->prepare("SELECT url_foto FROM usuario WHERE idusuario = :idusuario");
                $objRespuesta->bindParam(':idusuario', $idusuario);
                $objRespuesta->execute();
                $usuario = $objRespuesta->fetch();

                if ($usuario) {
                    $rutaAnterior = $usuario['url_foto'];
                    $rutaFolder = dirname($rutaAnterior);

                    $arrayArchivo = explode('.', $url_foto["name"]);
                    $nombreArchivo = uniqid('img-') . '.' . end($arrayArchivo);
                    $rutaNueva = $rutaFolder . '/' . $nombreArchivo;

                    if (move_uploaded_file($url_foto['tmp_name'], '../' . $rutaNueva)) {
                        $objRespuesta = Conexion::conectar()->prepare(
                            "UPDATE usuario SET nombre = :nombre, documento = :documento, email = :email, telefono = :telefono, url_foto = :url_foto WHERE idusuario = :idusuario"
                        );
                        $objRespuesta->bindParam(':nombre', $nombre);
                        $objRespuesta->bindParam(':documento', $documento);
                        $objRespuesta->bindParam(':email', $email);
                        $objRespuesta->bindParam(':telefono', $telefono);
                        $objRespuesta->bindParam(':url_foto', $rutaNueva);
                        $objRespuesta->bindParam(':idusuario', $idusuario);

                        if ($objRespuesta->execute()) {
                            unlink('../' . $rutaAnterior);
                            $mensaje = array("codigo" => "200", "mensaje" => "Usuario actualizado correctamente");
                        } else {
                            $mensaje = array("codigo" => "401", "mensaje" => "Error al actualizar el usuario");
                        }
                    }
                } else {
                    $mensaje = array("codigo" => "404", "mensaje" => "Usuario no encontrado");
                }
            } catch (Exception $e) {
                $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
            }

            return $mensaje;
        }

        public static function mdlCargarUsuario($idUsuario){
            $mensaje = array();
            try {
                $objRespuesta = Conexion::conectar()->prepare("SELECT * FROM usuario WHERE idusuario=:idusuario");
                $objRespuesta->bindParam(":idusuario",$idUsuario);
                $objRespuesta->execute();
                $Usuario = $objRespuesta->fetch();
                $mensaje = array("codigo"=>"200","Usuario"=>$Usuario);
                $objRespuesta = null;
            } catch (Exception $e) {
                $mensaje = array("codigo"=>"401","mensaje"=>$e->getMessage());
            }
            return $mensaje;
        }
    
}