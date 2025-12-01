<?php
require_once '../../functions/autoload.php';

$postData     = $_POST;
$datosArchivo = $_FILES;

$producto_id   = $postData['producto_id'] ?? null;
$datos_nombre  = $postData['datos_nombre'] ?? [];
$datos_valor   = $postData['datos_valor'] ?? [];
$imagenes      = $datosArchivo['imagenes'] ?? null;

$error = false;

/* ----------------------------------
| Validaciones básicas
+---------------------------------- */
if (!$producto_id || !is_numeric($producto_id)) {
    $errores[] = "Producto no encontrado.";
}

if (!is_array($datos_nombre) || !is_array($datos_valor) || count($datos_nombre) !== count($datos_valor)) {
    $errores[] = "Datos tecnicos incompletos.";
} else {
    foreach ($datos_nombre as $i => $nombre) {
        if (strlen(trim($nombre)) < 2) {
            $errores[] = "El nombre del dato técnico en la fila " . ($i + 1) . " es corto.";
        }
        if (strlen(trim($datos_valor[$i])) < 1) {
            $errores[] = "El valor del dato técnico en la fila " . ($i + 1) . " es obligatorio.";
        }
    }
}

if (!empty($imagenes) && isset($imagenes['name'])) {
    $permitidos = ['image/webp'];

    foreach ($imagenes['name'] as $i => $nombreArchivo) {
        $tipo = $imagenes['type'][$i];
        $size = $imagenes['size'][$i];

        if (!in_array($tipo, $permitidos)) {
            $errores[] = "El archivo {$nombreArchivo} debe ser WEBP.";
        }
        if ($size > 5 * 1024 * 1024) {
            $errores[] = "El archivo {$nombreArchivo} no puede superar los 5MB.";
        }
    }
}

if (!empty($errores)) {
    $url = '&error=' . urlencode(implode(' | ', $errores));
    header("Location: ../../?section=add_producto_info&id=$producto_id$url");
    exit;
}

/* ----------------------------------
| Guardar datos
+---------------------------------- */

try {

    // ----- DATOS TECNICOS ----- //
    DatosTecnicos::deleteTodosProducto($producto_id);

    foreach ($datos_nombre as $i => $nombre) {
        $valor = $datos_valor[$i];
        DatosTecnicos::insert($producto_id, $nombre, $valor);
    }

    // ----- IMAGENES ----- //
    $directorioBase   = '../../assets/img/productos';
    $carpetaProducto  = $directorioBase . '/' . $producto_id;

    $imagenesViejas = ProductoImagenes::getByProductoId($producto_id);
    foreach ($imagenesViejas as $img) {
        Imagen::borrarImagen($img->getUrl());
    }
    ProductoImagenes::deleteTodosProducto($producto_id);

    if (!is_dir($carpetaProducto)) {
        mkdir($carpetaProducto, 0777, true);
    }

    foreach ($imagenes['name'] as $i => $nombreArchivo) {
        $guardada = Imagen::subirImagen($carpetaProducto, [
            'name'     => $nombreArchivo,
            'tmp_name' => $imagenes['tmp_name'][$i],
            'type'     => $imagenes['type'][$i],
            'size'     => $imagenes['size'][$i],
            'error'    => $imagenes['error'][$i]
        ]);

        if ($guardada) {
            ProductoImagenes::insert($producto_id, $guardada);
        }
    }

} catch (\Throwable $e) {
    die("No se pudo guardar la información adicional: " . $e->getMessage());
}

header("Location: ../../?section=dashboard_productos");
exit;
