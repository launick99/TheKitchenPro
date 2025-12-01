<?php
    require_once '../../functions/autoload.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $id = $_GET['id'] ?? null;
    $error = false;

    try{
        $categoria = Categorias::getCategoriaPorId((int)$id);
        if(!$categoria->delete()){
            $error = true;
            Alerta::agregarAlerta("danger", "Error al eliminar categoria.");
        }
    }catch(Exception $e){
        $error = true;
        Alerta::agregarAlerta("danger", "Error al eliminar categoria: " . $e->getMessage());
    }
    if(!$error){
        Alerta::agregarAlerta("success", "Categoria Activada!");
    }
    header("Location: ../../?section=dashboard_categorias");
return;