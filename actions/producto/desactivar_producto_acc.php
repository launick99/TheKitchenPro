<?php
    require_once '../../functions/autoload.php';

    $id = $_GET['id'] ?? null;

    try{
        $producto = Producto::getProductById((int)$id);
        if(!$producto->delete()){
            die("Error al eliminar producto.");
        }
    }catch(Exception $error){
        die("Error al eliminar producto: " . $error->getMessage());
    }
    header("Location: ../../?section=dashboard_productos");
exit;