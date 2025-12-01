<?php
    require_once '../../functions/autoload.php';

    $nombre      = $_POST['nombre'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $activa      = isset($_POST['activa']) ? 1 : 0;
    $error = false;

    if(strlen($nombre) < 5) {
        Alerta::agregarAlerta("danger", "El nombre es obligatorio y debe ser mayor a 5 caracteres.");
        $error = true;
    }
    if(strlen($descripcion) < 15) {
        Alerta::agregarAlerta("danger", "La descripción es obligatoriay debe ser mayor a 15 caracteres.");
        $error = true;
    }

    if ($error) {
        $url = 
            '&nombre='      . urlencode($nombre) .
            '&descripcion=' . urlencode($descripcion).
            '&activa='      . urlencode($activa);

        header("Location: ../../?section=add_categoria&$url");
        return;
    }

    try {
        $idCategoria = Categorias::insert(
            $nombre,
            $descripcion,
            $activa
        );
    } catch (\Throwable $error) {
        Alerta::agregarAlerta("danger", 'no se puede crear la categoria: ' . $error->getMessage());
    }
    header("Location: ../../?section=dashboard_categorias");
?>