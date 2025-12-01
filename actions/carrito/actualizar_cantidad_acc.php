<?php
    require_once '../../functions/autoload.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $id = $_GET['id'] ?? null;
    $cantidad = $_GET['cantidad'] ?? null;

    try {
        if($id && $cantidad >= 1){
            Carrito::actualizarCantidad($id, $cantidad);
            Alerta::agregarAlerta("success", "cantidad actualizada");
        }
    } catch (\Throwable $th) {
        Alerta::agregarAlerta("danger", $th->getMessage());
    }

    header('Location: ../../?section=carrito');
    exit;
?>