<?php
require_once '../../functions/autoload.php';

$id = $_GET['id'] ?? null;

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
        '&error='       . implode(' | ', $errores) .
        '&nombre='      . $nombre .
        '&descripcion=' . $descripcion.
        '&activa='      . $activa;

    header("Location: ../../?section=editar_categoria&id=$id&$url");
    exit;
}

try {
    $categoria = Categorias::getCategoriaPorId($id);

    if (!$categoria) {
        throw new Exception("La categoría no existe.");
    }

    $categoria->setNombre($nombre);
    $categoria->setDescripcion($descripcion);
    $categoria->setActiva($activa);

    $categoria->update();
    header("Location: ../../?section=dashboard_categorias");


} catch (\Throwable $error) {
    die('Error al actualizar la categoría: ' . $error->getMessage());
}
