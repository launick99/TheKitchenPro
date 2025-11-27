<?php
    require_once '../../functions/autoload.php';
    $postData = $_POST;
    $datosArchivo = $_FILES;

    $directorio = '../../assets/img/productos';

    $nombre       = $postData['nombre'] ?? '';
    $descripcion  = $postData['descripcion'] ?? '';
    $precio       = $postData['precio'] ?? '';
    $stock        = $postData['stock'] ?? '';
    $stock_minimo = $postData['stock_minimo'] ?? '';
    $categorias   = $postData['categorias'] ?? [];
    $imagen       = $datosArchivo['imagen'] ?? null;

    $errores = [];

    /* ----------------------------------
    |  Validaciones
    +---------------------------------- */
    if(strlen(trim($nombre)) < 3) {
        $errores[] = "El nombre del producto es obligatorio y debe tener al menos 3 caracteres.";
    }
    if(strlen(trim($descripcion)) < 10) {
        $errores[] = "La descripción es obligatoria y debe tener al menos 10 caracteres.";
    }
    if(!is_numeric($precio) || $precio < 2000) {
        $errores[] = "El precio debe ser un número mayor o igual a 2000.";
    }
    if(!is_numeric($stock) || $stock < 1) {
        $errores[] = "El stock debe ser un número mayor a 0.";
    }
    if(!is_numeric($stock_minimo) || $stock_minimo < 0) {
        $errores[] = "El stock mínimo debe ser un número igual o mayor a 0.";
    }
    if(!is_array($categorias) || count($categorias) === 0) {
        $errores[] = "Debes seleccionar al menos una categoría.";
    }

    if($imagen) {
        $permitidos = ['image/webp'];
        if(!in_array($imagen['type'], $permitidos)) {
            $errores[] = "El archivo de imagen debe ser JPG, PNG o WEBP.";
        }
        if($imagen['size'] > 5*1024*1024) {
            $errores[] = "El archivo de imagen no puede superar los 5MB.";
        }
    }

    if(!empty($errores)) {
        $url = 
            '&error='      . urlencode(implode(' | ', $errores)) .
            '&nombre='     . urlencode($nombre) .
            '&descripcion='. urlencode($descripcion) .
            '&precio='     . urlencode($precio) .
            '&stock='      . urlencode($stock) .
            '&stock_minimo='. urlencode($stock_minimo);

        foreach($categorias as $cat) {
            $url .= '&categorias[]=' . urlencode($cat);
        }

        header("Location: ../../?section=add_producto$url");
        exit;
    }

    try {
        $imagen = Imagen::subirImagen($directorio, $imagen);
        $fecha = date('Y-m-d');

        $producto = Producto::insert(
            $nombre,
            $descripcion,
            $precio,
            $fecha,
            true,
            $imagen
        );


        foreach ($categorias as $categoria) {
            ProductoCategoria::insert(
                $producto,
                $categoria
            );
        }

        Stock::insert(
            $producto,
            $stock,
            $stock_minimo
        );

    } catch (\Throwable $e) {
        throw $e;
        
    }

    // Redirigir al dashboard de productos
    header("Location: ../../?section=add_producto_info");
    exit;

?>