<?php
    require_once '../../functions/autoload.php';

    $nombre      = $_POST['nombre'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $activa      = isset($_POST['activa']) ? 1 : 0;

    if(strlen($nombre) < 5) {
        $errores[] = "El nombre es obligatorio y debe ser mayor a 5 caracteres.";
    }
    if(strlen($descripcion) < 15) {
        $errores[] = "La descripción es obligatoriay debe ser mayor a 15 caracteres.";
    }

    if (!empty($errores)) {
        $url = 
            '&error='       . urlencode(implode(' | ', $errores)) .
            '&nombre='      . urlencode($nombre) .
            '&descripcion=' . urlencode($descripcion).
            '&activa='      . urlencode($activa);

        header("Location: ../../?section=add_categoria&$url");
        exit;
    }

    try {
        $idCategoria = Categorias::insert(
            $nombre,
            $descripcion,
            $activa
        );
    } catch (\Throwable $error) {
        die('no se puede crear la categoria: ' . $error->getMessage());
    }
    header("Location: ../../?section=dashboard_categorias");
?>