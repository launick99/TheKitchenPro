<?php
    require_once '../../functions/autoload.php';

    $id = $_GET['id'] ?? null;

    try{
        $categoria = Categorias::getCategoriaPorId((int)$id);
        if(!$categoria->delete()){
            die("Error al eliminar categoria.");
        }
    }catch(Exception $error){
        die("Error al eliminar categoria: " . $error->getMessage());
    }
    header("Location: ../../?section=dashboard_categorias");
exit;