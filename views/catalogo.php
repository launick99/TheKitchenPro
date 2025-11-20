<?php
    $catalogo = new Catalogo('datos/productos.json');

    $categoria = $_GET['categorias'] ?? null;
    $min = $_GET['min'] ?? null;
    $max = $_GET['max'] ?? null;
    $stock = $_GET['stock'] ?? null;

    $rangos = [
        '0-5000'            => 'Hasta $5.000',
        '5000-15000'        => '$5.000 a $15.000',
        '15000-30000'       => '$15.000 a $30.000',
        '25000-35000'       => '$25.000 a $35.000',
        '35000-45000'       => '$35.000 a $45.000',
        '45000-100000000'   => 'Más de $45.000',
    ];

    $categoriasDisponibles = $catalogo->getCategorias();

    //descomentado para los enlaces de home
    $productos = $categoria 
        ? $catalogo->filtrarPorCategoria($categoria)
        : $catalogo->getTodos();   

    // Filtro de búsqueda de texto
    if (!empty($_GET['buscar'])) {
        $busqueda = strtolower(trim($_GET['buscar']));
        $productos = array_filter($productos, function($producto) use ($busqueda) {
            return 
                str_contains(strtolower($producto->getNombre()), $busqueda) ||
                str_contains(strtolower($producto->getDescripcion()), $busqueda);
        });
    }
    // Filtro de rango de precio
    if (!empty($_GET['rango'])) {
        [$rMin, $rMax] = explode('-', $_GET['rango']);
        $productos = array_filter($productos, function($producto) use ($rMin, $rMax) {
            return $producto->getPrecio() >= $rMin && $producto->getPrecio() <= $rMax;
        });
    }
    // Filtro de rango de stock
    if (!empty($_GET['stock'])) {
        $productos = array_filter($productos, function($producto) {
            return $producto->estaDisponible();
        });
    }
?>

<section class="container my-5" id="productos">
    <h2 class="mb-4">Catálogo de Productos</h2>
    <div class="row">
        <div class="col-12 col-lg-4 col-xl-3">
        <form method="GET" class="d-flex flex-column mb-4">
                <input type="hidden" name="section" value="catalogo">
                <div class="form-group mb-3">
                    <label for="buscar">Buscar producto</label>
                    <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Ej: sartén, olla..." value="<?= $_GET['buscar'] ?? '' ?>">
                </div>
                <!-- me molesta el validador -->
                <div class="form-group mb-3">
                    <label>Categorías</label>
                    <?php
                    foreach ($categoriasDisponibles as $id => $cat) {
                        $id_categoria = strtolower(trim($cat));
                        //var_dump($id_categoria);
                        $checked = isset($_GET['categorias']) && in_array($id_categoria, $_GET['categorias']) ? 'checked' : '';
                        echo "
                            <div class='form-check'>
                                <input class='form-check-input' type='checkbox' name='categorias[]' value='$id_categoria' id='cat_$id_categoria' $checked>
                                <label class='form-check-label' for='cat_$id_categoria'>" . ucwords(str_replace('_', ' ', $cat)) . "</label>
                            </div>
                        ";
                    }
                    ?>
                </div>
                <!-- Rango de Precio -->
                <div class="form-group mb-3">
                    <label for="rango">Rango de precio</label>
                    <select name="rango" id="rango" class="form-select">
                        <option value="">Seleccioná un rango</option>
                        <?php
                            foreach ($rangos as $valor => $etiqueta) {
                                $selected = (($_GET['rango'] ?? '') == $valor) ? 'selected' : '';
                                echo "<option value=\"$valor\" $selected>$etiqueta</option>";
                            }
                        ?>
                    </select>
                </div>
                <!-- Check stck -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="stock" value="1" id="stock" <?= isset($_GET['stock']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="stock">Solo productos en stock</label>
                </div>
                <!-- Filtrar -->
                <button type="submit" class="btn btn-warning mt-2">Filtrar</button>
            </form>
        </div>
        <div class="col-12 col-lg-8 col-xl-9">
            <div class="row">
                <?php if(empty($productos)): ?>
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            No se encontraron productos :(
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($productos as $producto): ?>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="<?= $producto->getImagen() ?>" class="card-img-top" alt="<?= $producto->getNombre() ?>" Style="max-height:250px;">
                                <div class="card-body">
                                    <h3 class="card-title h5"><?= $producto->getNombre() ?></h3>
                                    <p class="card-text"><?= $producto->getDescripcionCorta() ?></p>
                                </div>
                                <div class="card-footer" style="border-top: none; background: none;">
                                    <div class="d-flex justify-content-between align-items-center my-2">
                                        <p class="card-text">
                                            <?php if ($producto->estaDisponible()): ?>
                                                <span class="badge bg-success">En stock</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Sin stock</span>
                                            <?php endif; ?>
                                        </p>
                                        <p class="card-text text-end fw-bold h4"><?= $producto->formatearPrecio() ?></p>
                                    </div>
                                    <a href="index.php?section=detalle&id=<?= $producto->getId() ?>" class="btn btn-warning w-100">Ver más</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>