<?php
    $categoria = Categorias::getCategoriaPorId($_GET['categoria'] ?? null);

    if(!$categoria){
       include_once('views/error/404.php');
       include_once('views/plantillas/footer.php');
       return;
    }

    $rangos = [
        '0-5000'            => 'Hasta $5.000',
        '5000-15000'        => '$5.000 a $15.000',
        '15000-30000'       => '$15.000 a $30.000',
        '25000-35000'       => '$25.000 a $35.000',
        '35000-45000'       => '$35.000 a $45.000',
        '45000-100000000'   => 'Más de $45.000',
    ];

    $rango = isset($_GET['rango']) ? explode('-', $_GET['rango']) : [];
    $stock = $_GET['stock'] ?? null;

    $productos = (new Producto())->filtrarProductos(
        buscar: $_GET['buscar'] ?? null,
        categorias: array($categoria->getId()),
        rangoPrecio: $rango,
        soloConStock: isset($stock)
    );
    
?>

<section class="container my-5" id="productos">
    <h2><?= $categoria->getNombre() ?></h2>
    <p class="mb-4"><?= $categoria->getDescripcion() ?></p>
    <div class="row">
        <div class="col-12 col-lg-4 col-xl-3">
        <form method="GET" class="d-flex flex-column mb-4">
                <input type="hidden" name="section" value="catalogo">
                <input type="hidden" name="categorias[]" value="<?= $categoria->getId() ?>">
                <div class="form-group mb-3">
                    <label for="buscar">Buscar producto</label>
                    <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Ej: sartén, olla..." value="<?= $_GET['buscar'] ?? '' ?>">
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
                <!-- Check stock -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="stock" value="1" id="stock" <?= isset($_GET['stock']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="stock">Solo productos en stock</label>
                </div>
                <!-- Filtrar -->
                <button type="submit" class="btn btn-kitchenpro btn btn-warning mt-2">Filtrar</button>
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
                        <div class="col-12 col-md-6 col-xl-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="<?= $producto->getImagen() ?>" class="card-img-top" alt="<?= $producto->getNombre() ?>" Style="max-height:250px;">
                                <div class="card-body">
                                    <h3 class="card-title h5"><?= $producto->getNombre() ?></h3>
                                    <p class="card-text"><?= $producto->getDescripcionCorta() ?></p>
                                </div>
                                <div class="card-footer" style="border-top: none; background: none;">
                                    <div class="d-flex justify-content-between align-items-center my-2">
                                        <p class="card-text">
                                            <span class="badge bg-<?= $producto->getStock()?->getColor() ?>">
                                                <?= $producto->getStock()?->getEstado() ?>
                                            </span>
                                        </p>
                                        <p class="card-text text-end fw-bold h4"><?= $producto->formatearPrecio() ?></p>
                                    </div>
                                    <a href="index.php?section=detalle&id=<?= $producto->getId() ?>" class="btn btn-kitchenpro btn btn-warning w-100">Ver más</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>