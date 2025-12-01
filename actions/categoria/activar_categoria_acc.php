<?php
    require_once '../../functions/autoload.php';

    $id = $_GET['id'] ?? null;

    try{
        $categoria = Categorias::getCategoriaPorId((int)$id);
        if(!$categoria->restore()){
            Alerta::agregarAlerta("danger", "Error al restaurar categoria.");
        }
    }catch(Exception $error){
        Alerta::agregarAlerta("danger", "Error al restaurar categoria: " . $error->getMessage());
    }
    header("Location: ../../?section=dashboard_categorias");
exit;