<?php
    require_once '../../functions/autoload.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $id = $_GET['id'] ?? false;

    if($id){
        Carrito::remover($id);
        Alerta::agregarAlerta("success", "Producto Eliminado");
        header('location: ../../index.php?section=carrito');
        return;
    }
    Alerta::agregarAlerta("danger", "No se pudo eliminar el producto del carrito");
    header('location: ../../index.php');
    return;
?>