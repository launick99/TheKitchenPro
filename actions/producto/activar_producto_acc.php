<?php
    require_once '../../functions/autoload.php';

    $id = $_GET['id'] ?? null;

    try{
        $producto = Producto::getProductById((int)$id);
        if(!$producto->restore()){
            Alerta::agregarAlerta("danger", "Error al restaurar producto.");
        }
    }catch(Exception $error){
        Alerta::agregarAlerta("danger", "Error al restaurar producto.");
    }
    header("Location: ../../?section=dashboard_productos");
exit;