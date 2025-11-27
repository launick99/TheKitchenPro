<?php
    require_once '../../functions/autoload.php';

    $nombre      = $_POST['nombre'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $activa      = isset($_POST['activa']) ? 1 : 0;

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