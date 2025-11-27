<?php
    require_once '../../functions/autoload.php';

    $postData     = $_POST;
    $datosArchivo = $_FILES;

    $producto_id   = $postData['producto_id'] ?? null;
    $datos_nombre  = $postData['datos_nombre'] ?? [];
    $datos_valor   = $postData['datos_valor'] ?? [];
    $imagenes      = $datosArchivo['imagenes'] ?? null;

    $errores = [];

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
                $errores[] = "El nombre del dato tenico en la fila " . ($i+1) . " es corto.";
            }
            if (strlen(trim($datos_valor[$i])) < 1) {
                $errores[] = "El valor del dato tecnico en la fila " . ($i+1) . " es obligatorio.";
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
        foreach ($datos_nombre as $i => $nombre) {
            $valor = $datos_valor[$i];
            DatosTecnicos::deleteTodosProducto($producto_id);
            DatosTecnicos::insert($producto_id, $nombre, $valor);
        }

        $directorioBase = '../../assets/img/productos';
        $carpetaProducto = $directorioBase . '/' . $producto_id;

        $imagenesViejas = ProductoImagenes::getByProductoId($producto_id);
        foreach ($imagenesViejas as $img) {
            Imagen::borrarImagen($img->getUrl());
        }


        if (!is_dir($carpetaProducto)) {
            mkdir($carpetaProducto, 0777, true);
        }

        foreach ($imagenes['name'] as $i => $nombreArchivo) {
            if ($imagenes['error'][$i] === UPLOAD_ERR_OK) {
                $archivoTmp = $imagenes['tmp_name'][$i];
                $imagen = Imagen::subirImagen($carpetaProducto, [
                    'name' => $nombreArchivo,
                    'tmp_name' => $archivoTmp,
                    'type' => $imagenes['type'][$i],
                    'size' => $imagenes['size'][$i],
                    'error' => $imagenes['error'][$i]
                ]);

            }
        }
        ProductoImagenes::deleteTodosProducto($producto_id);
        ProductoImagenes::insert($producto_id, $imagen);
    } catch (\Throwable $e) {
        die("No se pudo guardar la información adicional: " . $e->getMessage());
    }

    header("Location: ../../?section=dashboard_productos");
    exit;
?>
