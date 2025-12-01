<?php
    require_once '../../functions/autoload.php';

    $id = $_GET['id'] ?? null;
    $error = false;

    try{
        $producto = Producto::getProductById((int)$id);
        if(!$producto->restore()){
            Alerta::agregarAlerta("danger", "Error al restaurar producto.");
            $error = true;
        }
    }catch(Exception $e){
        Alerta::agregarAlerta("danger", "Error al restaurar producto.");
        $error = true;
    }
    if (!$error) {
        Alerta::agregarAlerta("success", "Producto Activado correctamente");
    }
    header("Location: ../../?section=dashboard_productos");
return;