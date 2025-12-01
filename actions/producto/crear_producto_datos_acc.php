<?php
    require_once '../../functions/autoload.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $postData     = $_POST;
    $datosArchivo = $_FILES;

    $producto_id        = $postData['producto_id']          ?? null;
    $datos_nombre       = $postData['datos_nombre']         ?? [];
    $datos_valor        = $postData['datos_valor']          ?? [];
    $imagenes           = $datosArchivo['imagenes']         ?? null;
    $imagenesEliminadas = $postData['imagenes_eliminar']    ?? null;

    $error = false;

    /* ----------------------------------
    | Validaciones básicas
    +---------------------------------- */
    if (!$producto_id || !is_numeric($producto_id)) {
        Alerta::agregarAlerta("danger", "Producto no encontrado.");
        $error = true;
    }

    if (!is_array($datos_nombre) || !is_array($datos_valor) || count($datos_nombre) !== count($datos_valor)) {
        Alerta::agregarAlerta("danger", "Datos tecnicos incompletos.");
        $error = true;
    } else {
        foreach ($datos_nombre as $i => $nombre) {
            if (strlen(trim($nombre)) < 2) {
                Alerta::agregarAlerta("danger", "El nombre del dato técnico en la fila " . ($i + 1) . " es corto.");
                $error = true;
            }
            if (strlen(trim($datos_valor[$i])) < 1) {
                Alerta::agregarAlerta("danger", "El valor del dato técnico en la fila " . ($i + 1) . " es obligatorio.");
                $error = true;
            }
        }
    }

    // ----- check imagenes ----- //
    if (!empty($imagenes)) {
        $permitidos = ['image/webp'];
        foreach ($imagenes['name'] as $i => $nombreArchivo) {
            $tipo = $imagenes['type'][$i];
            $size = $imagenes['size'][$i];

            if (!in_array($tipo, $permitidos)) {
                Alerta::agregarAlerta("danger", "El archivo {$nombreArchivo} debe ser WEBP.");
                $error = true;
            }
            if ($size > 5 * 1024 * 1024) {
                Alerta::agregarAlerta("danger", "El archivo {$nombreArchivo} no puede superar los 5MB.");
                $error = true;
            }
        }
    }

    if ($error) {
        header("Location: ../../?section=add_producto_info&id=$producto_id$url");
        return;
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

        if (!is_dir($carpetaProducto)) {
            mkdir($carpetaProducto, 0777, true);
        }

        if (!empty($imagenes)) {
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
        }

        foreach($imagenesEliminadas as $idImagen){
            $productoImagen = ProductoImagenes::getById($idImagen);
            Imagen::borrarImagen($productoImagen->getUrl("$directorioBase/"));
            ProductoImagenes::delete($productoImagen->getId());
        }
    } catch (\Throwable $e) {
        Alerta::agregarAlerta("danger", "No se pudo guardar la información adicional: " . $e->getMessage());
    }

    Alerta::agregarAlerta("success", "Producto Guardado!");
    header("Location: ../../?section=dashboard_productos");
    return;
?>
