<?php
    require_once '../../functions/autoload.php';

    $id = $_GET['id'] ?? null;
    $error = false;

    try{
        $categoria = Categorias::getCategoriaPorId((int)$id);
        if(!$categoria->restore()){
            Alerta::agregarAlerta("danger", "Error al restaurar categoria.");
            $error = true;
        }
    }catch(Exception $e){
        Alerta::agregarAlerta("danger", "Error al restaurar categoria: " . $e->getMessage());
        $error = true;
    }
    if(!$error){
        Alerta::agregarAlerta("success", "Categoria Activada!");
    }
    header("Location: ../../?section=dashboard_categorias");
return;