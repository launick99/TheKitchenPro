<?php
    require_once '../../functions/autoload.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $id = $_GET['id'] ?? null;
    $error = false;

    try{
        $producto = Producto::getProductById((int)$id);
        if(!$producto->delete()){
            Alerta::agregarAlerta("danger", "Error al eliminar producto.");
            $error = true;
        }
    }catch(Exception $e){
        Alerta::agregarAlerta("danger", "Error al eliminar producto: " . $e->getMessage());
        $error = true;
    }
    if(!$error){
        Alerta::agregarAlerta("success", "Producto dado de baja!");
    }
    header("Location: ../../?section=dashboard_productos");
return;