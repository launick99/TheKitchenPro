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

    $error = false;

    /* ----------------------------------
    |  Validaciones
    +---------------------------------- */
    if(strlen(trim($nombre)) < 3) {
        Alerta::agregarAlerta("danger", "El nombre del producto es obligatorio y debe tener al menos 3 caracteres.");
        $error = true;
    }

    if(strlen(trim($descripcion)) < 10) {
        Alerta::agregarAlerta("danger", "La descripción es obligatoria y debe tener al menos 10 caracteres.");
        $error = true;
    }

    if(!is_numeric($precio) || $precio < 2000) {
        Alerta::agregarAlerta("danger", "El precio debe ser un número mayor o igual a 2000.");
        $error = true;
    }

    if(!is_numeric($stock) || $stock < 1) {
        Alerta::agregarAlerta("danger", "El stock debe ser un número mayor a 0.");
        $error = true;
    }

    if(!is_numeric($stock_minimo) || $stock_minimo < 0) {
        Alerta::agregarAlerta("danger", "El stock mínimo debe ser un número igual o mayor a 0.");
        $error = true;
    }

    if(!is_array($categorias) || count($categorias) === 0) {
        Alerta::agregarAlerta("danger", "Debes seleccionar al menos una categoría.");
        $error = true;
    }

    if($imagen['name']) {
        $permitidos = ['image/webp'];

        if(!in_array($imagen['type'], $permitidos)) {
            Alerta::agregarAlerta("danger", "El archivo de imagen debe ser WEBP.");
            $error = true;
        }

        if($imagen['size'] > 5*1024*1024) {
            Alerta::agregarAlerta("danger", "El archivo de imagen no puede superar los 5MB.");
            $error = true;
        }
    }

    /* ----------------------------------
    |  Errores
    +---------------------------------- */
    if($error) {
        $url = 
            '&id='         . urlencode($id) .
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
    Alerta::agregarAlerta("success", "Producto guardado correctamente!");
    header("Location: ../../?section=add_producto_info&id=$id");
    return;
?>