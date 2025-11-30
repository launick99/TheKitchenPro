<?php
    $categorias = Categorias::getTodas();
    
    $producto = Producto::getProductById($_GET['id']);
    $stock = $producto->getStock();
    $categoriasProducto = $producto->getCategorias();

    if(isset($_GET['error'])){
        $producto->setNombre($_GET['nombre'] ?? '');
        $producto->setDescripcion($_GET['descripcion'] ?? '');
        $producto->setPrecio($_GET['precio'] ?? '');
        $stock->setStock($_GET['stock'] ?? '');
        $stock->setStockMinimo($_GET['stock_minimo'] ?? '');
        $errorMensaje = $_GET['error'];
    }
?>

<section class="container my-5">
    <h2>Crear un nuevo producto</h2>
    <form action="actions/producto/actualizar_producto_acc.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" id="id" name="id" value="<?= $producto->getId() ?>">
        <fieldset>
            <legend>Información del Producto</legend>
            <div class="row">
                <div class="col-12 col-md-7">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del producto</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $producto->getNombre() ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="10"
                                  style="resize: none" required><?= $producto->getDescripcion() ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Pesos</span>
                            <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="2000" value="<?= $producto->getPrecio() ?>" required>
                            <span class="input-group-text">$</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen</label>
                        <input type="file" name="imagen" id="imagen" class="form-control">
                        <p class="text-muted">Las imágenes solo se aceptan en .webp</p>
                    </div>
                </div>
                <div class="col-12 col-md-1"></div>
                <div class="col-12 col-md-4">
                    <div class="preview" style="height: 512px;">
                        <p>Preview Imagen del producto</p>
                        <img src="<?= $producto->getImagen() ?>" id="preview" class="mx-auto d-block rounded img-fluid" alt="Imagen del nuevo producto">
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Información sobre Stock</legend>
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="stock" name="stock" min="1" value="<?= $stock->getStock() ?>" required>
                        <p class="text-muted">Stock actual en depósito</p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="stock_minimo" class="form-label">Stock Mínimo</label>
                        <input type="number" class="form-control" id="stock_minimo" name="stock_minimo" min="0" value="<?= $stock->getStockMinimo() ?: 0 ?>" required>
                        <p class="text-muted">Advierte cuando hay poco stock del producto</p>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Categorías</legend>
            <p>Seleccione una o más</p>
            <div class="card p-2">
                <div class="row">
                    <?php foreach($categorias as $categoria) { ?>
                        <?php if($categoria->getActiva() || in_array($categoria, $categoriasProducto)) { ?>
                            <?php
                                $checked = in_array($categoria, $categoriasProducto) ? 'checked' : '';
                            ?>
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="form-check my-1">
                                    <input class="form-check-input" type="checkbox" name="categorias[]"
                                           value="<?= $categoria->getId() ?>" id="cat_<?= $categoria->getId() ?>"
                                           <?= $checked ?>>
                                    <label class="form-check-label" for="cat_<?= $categoria->getId() ?>">
                                        <?= $categoria->getNombre() ?>
                                    </label>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </fieldset>

        <div class="my-5">
            <button type="submit" class="btn btn-kitchenpro btn btn-success">Actualizar Producto</button>
        </div>
    </form>
</section>

<script>
    document.getElementById('imagen').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('preview');
        if(!file){
            preview.src = '<?= Imagen::imageNotFound() ?>';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(event){
            preview.src = event.target.result;
        };
        reader.readAsDataURL(file);
    });
</script>