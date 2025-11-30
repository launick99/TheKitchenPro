<?php
    require_once '../../functions/autoload.php';
    $postData = $_POST;
    $datosArchivo = $_FILES;

    $directorio = '../../assets/img/productos/';

    $id       = $postData['id'] ?? '';
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

    if($imagen['name']) {
        $permitidos = ['image/webp'];
        if(!in_array($imagen['type'], $permitidos)) {
            $errores[] = "El archivo de imagen debe ser WEBP.";
        }
        if($imagen['size'] > 5*1024*1024) {
            $errores[] = "El archivo de imagen no puede superar los 5MB.";
        }
    }

    /* ----------------------------------
    |  Errores
    +---------------------------------- */
    if(!empty($errores)) {
        $url = 
            '&id='         . urlencode($id) .
            '&error='      . urlencode(implode(' | ', $errores)) .
            '&nombre='     . urlencode($nombre) .
            '&descripcion='. urlencode($descripcion) .
            '&precio='     . urlencode($precio) .
            '&stock='      . urlencode($stock) .
            '&stock_minimo='. urlencode($stock_minimo);

        foreach($categorias as $cat) {
            $url .= '&categorias[]=' . urlencode($cat);
        }

        header("Location: ../../?section=editar_producto$url");
        return;
    }

    /* ----------------------------------
    |  Guardar
    +---------------------------------- */
    $producto = Producto::getProductById($id);
    $existe = $imagen['name'] ? true : false;

    if($existe){
        Imagen::borrarImagen($producto->getImagen($directorio));
        $imagen = Imagen::subirImagen($directorio, $imagen);
    }

    try {
        $actualizado = Producto::update(
            $id,
            $nombre,
            $descripcion,
            $precio,
            $producto->getFechaIngreso(),
            true,
            $existe ? $imagen : $producto->getImagenReal()
        );

        ProductoCategoria::deleteTodosProducto($id);
        foreach ($categorias as $categoria) {
            ProductoCategoria::insert(
                $id,
                $categoria
            );
        }

        Stock::update(
            $id,
            $stock,
            $stock_minimo
        );

    } catch (\Throwable $e) {
        throw $e;
    }

    // ir a otra pantalla para agregar info
    header("Location: ../../?section=add_producto_info&id=$id");
    exit;
?>