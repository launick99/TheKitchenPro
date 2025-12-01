<?php
    $id = $_GET['id'] ?? null;
    $producto = Producto::getProductById((int)$id);

    if (!$producto) {
        echo "<div class='alert alert-danger'>Producto no encontrado.</div>";
        return;
    }

    // Algunas variables útiles
    $nombre = $producto->getNombre();
    $sku = $producto->getId();
    $descripcion = $producto->getDescripcion();
    $precio = $producto->formatearPrecio();
    $imagen = $producto->getImagen();
    $imagenes = $producto->getImagenes() ?? "";
    $datos = $producto->getDatosTecnicos() ?? [];

    $categorias = $producto->getCategorias() ?? [];

    $colores = ['primary', 'secondary', 'success', 'danger', 'warning'];

    $productosRelaciones = $producto->getProductosSimilares();
?>
<div class="container my-5">
    <section class="row">
        <!-- Galería de imágenes -->
        <div class="col-12 col-md-6">
            <div class="product-main-image">
                <img id="mainImage" class="img-thumbnail" src="<?= $imagen ?>" alt="Imagen principal del producto">
            </div>
            <div class="d-flex flex-wrap galeria-productos py-1">
                <img class="img-thumbnail active small-image" src="<?= $imagen ?>" alt="Imagen principal del producto" onclick="changeImage(this)">
                <?php foreach ($imagenes as $img): ?>
                    <img src="<?= $img->getUrl() ?>" alt="<?= $nombre ?>" class="img-thumbnail small-image" onclick="changeImage(this)">
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-12 col-md-1"></div>
        <!-- Detalles del producto -->
        <div class="col-12 col-md-5">
            <h2><?= $nombre ?></h2>
            <!-- estaba como sku en el trabajo del semetre pasado -->
            <p class="text-muted">SKU: <?= $sku ?></p> 
            <p class="fw-bold h2 text-success my-5"><?= $precio ?></p>
            <div class="my-5">
                <?php foreach($categorias as $c => $categoria){ ?>
                    <?php if($c >= 5){ break; } ?>
                    <a href="?section=categoria&categoria=<?= $categoria->getId() ?>" class="btn btn-<?= $colores[$c] ?> btn-sm">
                        <?= $categoria->getNombre() ?>
                    </a>
                <?php } ?>
            </div>
            <h3>Facilidades de pago:</h3>
            <ul class="list-unstyled">
                <li class="my-1">
                    <i class="fa-solid fa-piggy-bank fa-lg text-warning me-2"></i>
                    <span>Pago a meses sin intereses (6, 12 y 18 meses).</span>
                </li>
                <li class="my-1">
                    <i class="fa-solid fa-building-columns fa-lg text-warning me-2"></i>
                    <span>Transferencia bancaria.</span>    
                </li>
                <li class="my-1">
                    <i class="fa-regular fa-credit-card fa-lg text-warning me-2"></i>
                    <span>Pago con tarjeta de crédito y débito.</span>    
                </li>
            </ul>
            <div class="d-flex flex-col flex-md-row gap-5 justify-content-start my-5">
                <!-- No hace nada -->
                <div class="d-flex gap-1 justify-content-start">
                    <form action="actions/carrito/agregar_item_acc.php" method="GET">
                        <div class="input-group mb-3">
                            <input type="hidden" name="id" id="id" value="<?= $producto->getId() ?>">
                            <input class="form-control" id="cantidad" name="cantidad" type="number" min="1" max="<?= $producto->getStock()?->getStock() ?>" value="1">
                            <button class="btn btn-warning">
                                <i class="fa-solid fa-cart-shopping fa-lg me-2" style="width:32px"></i>
                                <span class="fw-bold">AGREGAR</span>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="mb-3">
                    <button class="btn btn-warning">
                        <i class="fa-solid fa-credit-card fa-lg me-2" style="width:32px"></i>
                        <span class="fw-bold">COMPRAR</span>
                    </button>
                </div>
            </div>
        </div>
    </section>
    <section class="my-5">
        <h3 class="mb-4">Descripción</h3>
        <p class="text-justify">
            <?= nl2br($descripcion)  ?>
        </p>
    </section>
    <?php if (!empty($datos)): ?>
        <section class="mt-1">
            <h3>Datos Técnicos</h3>
            <ul class="list-group">
                <?php foreach ($datos as $c => $valor): ?>
                    <li class="list-group-item">
                        <span class="fw-bold"><?= $valor->getClave() ?>:</span> <?= $valor->getValor() ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>
    <section class="my-5">
        <h3>Productos Relacionados</h3>
        <div class="row">
            <?php foreach ($productosRelaciones as $productoRelacionado){ ?>
                <div class="col-12 col-md-6 col-xl-3 my-1">
                    <a href="index.php?section=detalle&id=<?= $productoRelacionado->getId() ?>">
                        <div class="card overflow-hidden">
                            <div class="ratio ratio-1x1 border-bottom">
                                <img src="<?= $productoRelacionado->getImagen() ?>" alt="<?= $productoRelacionado->getNombre() ?>">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-truncate"><?= $productoRelacionado->getNombre() ?></h5>
                                <p class="card-text my-2"><?= $productoRelacionado->getDescripcionCorta() ?></p>
                                <p class="card-text text-end fw-bold h4"><?= $producto->formatearPrecio() ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </section>
</div>

<script>
    function changeImage(element) {
        document.getElementById('mainImage').src = element.src;
        document.querySelectorAll('.galeria-productos .img-thumbnail').forEach(item => {
            item.classList.remove('active');
        });
        element.classList.add('active');
    }
</script>