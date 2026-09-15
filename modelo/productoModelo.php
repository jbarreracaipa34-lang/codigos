<?php
include_once "conexion.php";
include_once "folderModelo.php";

class ProductoModelo {
    public static function mdlListarProductos() {
        $mensaje = array();
        try {
            $objRespuesta = Conexion::conectar()->prepare("SELECT * FROM productos");
            $objRespuesta->execute();
            $listarProductos = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
            $objRespuesta = null;
            $mensaje = array("codigo" => "200", "listarProductos" => $listarProductos);
        } catch (Exception $e) {
            $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }

    public static function mdlCargarProducto($idProducto){
        $mensaje = array();
        try {
            $objRespuesta = Conexion::conectar()->prepare("SELECT * FROM productos WHERE idproducto=:idproducto");
            $objRespuesta->bindParam(":idproducto",$idProducto);
            $objRespuesta->execute();
            $Producto = $objRespuesta->fetch();
            $mensaje = array("codigo"=>"200","Producto"=>$Producto);
            $objRespuesta = null;
        } catch (Exception $e) {
            $mensaje = array("codigo"=>"401","mensaje"=>$e->getMessage());
        }
        return $mensaje;
    }

    public static function mdlRegistrarProducto($stock, $precio, $descripcion, $url_Foto_pro) {
        $mensaje = FolderModelo::mdlCrearFolder($stock);

        if ($mensaje["codigo"] == "200") {
            $ruta = $mensaje["ruta"];
            $arrayArchivo = explode('.', $url_Foto_pro["name"]);
            $nombreArchivo = uniqid('img-') . '.' . end($arrayArchivo);
            $rutaFinal = $ruta . $nombreArchivo;

            if (move_uploaded_file($url_Foto_pro['tmp_name'], '../' . $ruta . $nombreArchivo)) {
                try {
                    $objRespuesta = Conexion::conectar()->prepare("INSERT INTO productos(stock, precio, descripcion, url_Foto_pro) VALUES(:stock, :precio, :descripcion, :url_Foto_pro)");
                    $objRespuesta->bindParam(":stock", $stock);
                    $objRespuesta->bindParam(":precio", $precio);
                    $objRespuesta->bindParam(":descripcion", $descripcion);
                    $objRespuesta->bindParam(":url_Foto_pro", $rutaFinal);

                    if ($objRespuesta->execute()) {
                        $mensaje = array("codigo" => "200", "mensaje" => "Producto registrado con éxito.");
                    } else {
                        $mensaje = array("codigo" => "401", "mensaje" => "No se pudo registrar el producto.");
                    }
                } catch (Exception $e) {
                    $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
                }
            } else {
                $mensaje = array("codigo" => "401", "mensaje" => "Error al subir la imagen.");
            }
        }
        return $mensaje;
    }

    public static function mdlEditarProducto($idProducto, $stock, $precio, $descripcion, $url_Foto_pro) {
        $mensaje = array();
    
        try {
            $conexion = Conexion::conectar();
            $conexion->beginTransaction();
    
            // Toma la imagen actual
            $imagenActual = self::mdlObtenerImagenActual($idProducto);
    
            // Esto es para la imagen actual
            $rutaFinal = $imagenActual;
            if ($url_Foto_pro && $url_Foto_pro['tmp_name']) {
                // Crear la carpeta para la imagen
                $mensajeFolder = FolderModelo::mdlCrearFolder($stock);
                if ($mensajeFolder["codigo"] !== "200") {
                    throw new Exception("Error al crear el directorio para la imagen.");
                }
    
                $ruta = $mensajeFolder["ruta"];
                $arrayArchivo = explode('.', $url_Foto_pro["name"]);
                $nombreArchivo = uniqid('img-') . '.' . end($arrayArchivo);
                $rutaFinal = $ruta . $nombreArchivo;
    
                // Eliminar la imagen anterior
                if ($imagenActual && file_exists('../' . $imagenActual)) {
                    unlink('../' . $imagenActual);
                }
    
                // Subir la nueva imagen
                if (!move_uploaded_file($url_Foto_pro['tmp_name'], '../' . $rutaFinal)) {
                    throw new Exception("Error al subir la nueva imagen.");
                }
            }
    
            
    
            $objRespuesta = $conexion->prepare("UPDATE productos SET stock = :stock, precio = :precio, descripcion = :descripcion, url_Foto_pro = :url_Foto_pro WHERE idProducto = :idProducto");
            $objRespuesta->bindParam(":idProducto", $idProducto, PDO::FETCH_ASSOC);
            $objRespuesta->bindParam(":stock", $stock, PDO::FETCH_ASSOC);
            $objRespuesta->bindParam(":precio", $precio, PDO::FETCH_ASSOC);
            $objRespuesta->bindParam(":descripcion", $descripcion, PDO::FETCH_ASSOC);
            $objRespuesta->bindParam(":url_Foto_pro", $rutaFinal, PDO::FETCH_ASSOC);
    
            if ($objRespuesta->execute()) {
                $conexion->commit();
                $mensaje = array("codigo" => "200", "mensaje" => "Producto actualizado con éxito.");
            } else {
                throw new Exception("Error al actualizar el producto.");
            }
        } catch (Exception $e) {
            if (isset($conexion)) {
                $conexion->rollBack();
            }
            $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
        }
    
        return $mensaje;
    }
    
    private static function mdlObtenerImagenActual($idProducto) {
        try {
            $conexion = Conexion::conectar();
            $query = "SELECT url_Foto_pro FROM productos WHERE idProducto = :idProducto";
            $objRespuesta = $conexion->prepare($query);
            $objRespuesta->bindParam(":idProducto", $idProducto, PDO::FETCH_ASSOC);
            $objRespuesta->execute();
    
            return $objRespuesta->fetchColumn();
        } catch (Exception $e) {
            return null; 
        }
    }

    public static function mdlEliminarProducto($idProducto) {
        $mensaje = array();
    
        try {
            $objRespuesta = Conexion::conectar()->prepare("SELECT url_Foto_pro FROM productos WHERE idProducto = :idProducto");
            $objRespuesta->bindParam(':idProducto', $idProducto);
            $objRespuesta->execute();
            $producto = $objRespuesta->fetch();
    
            if ($producto) {
                $folder = dirname($producto['url_Foto_pro']);
                if (!empty($producto['url_Foto_pro']) && file_exists('../' . $producto['url_Foto_pro'])) {
                    if (!unlink('../' . $producto['url_Foto_pro'])) {
                        throw new Exception("Error al eliminar la imagen asociada al producto.");
                    }
                }
    
                $objRespuesta = Conexion::conectar()->prepare("DELETE FROM productos WHERE idProducto = :idProducto");
                $objRespuesta->bindParam(':idProducto', $idProducto);
    
                if ($objRespuesta->execute()) {
                    $mensaje = array("codigo" => "200", "mensaje" => "Producto eliminado correctamente");
                } else {
                    $mensaje = array("codigo" => "401", "mensaje" => "Error al eliminar el producto");
                }
            } else {
                $mensaje = array("codigo" => "404", "mensaje" => "Producto no encontrado");
            }
        } catch (Exception $e) {
            $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
        }
    
        return $mensaje;
    }

    public static function mdlCargarProductos($idProducto){
        $mensaje = array();
        try {
            $objRespuesta = Conexion::conectar()->prepare("SELECT * FROM productos WHERE idProducto=:idProducto");
            $objRespuesta->bindParam(":idProducto",$idProducto);
            $objRespuesta->execute();
            $Producto = $objRespuesta->fetch();
            $mensaje = array("codigo"=>"200","Producto"=>$Producto);
            $objRespuesta = null;
        } catch (Exception $e) {
            $mensaje = array("codigo"=>"401","mensaje"=>$e->getMessage());
        }
        return $mensaje;
    }
    
}