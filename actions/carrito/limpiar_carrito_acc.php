<?php
    require_once '../../functions/autoload.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    Carrito::limpiar();
    Alerta::agregarAlerta("success", "carrito vaciado!");

    header('Location: ../../?section=catalogo');
    exit;
?>