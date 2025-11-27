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
?>
<main class="container my-5">
    <div class="row">
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
        <!-- Detalles del producto -->
        <div class="col-12 col-md-6">
            <h2><?= $nombre ?></h2>
            <!-- estaba como sku en el trabajo del semetre pasado -->
            <p class="text-muted">SKU: <?= $sku ?></p> 
            <p><?= $descripcion ?></p>
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
            <!-- No hace nada -->
            <button class="btn btn-kitchenpro btn btn-warning w-100">
                Comprar ahora
            </button>
        </div>
    </div>

    <?php if (!empty($datos)): ?>
        <section class="mt-5">
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
    <script>
        function changeImage(element) {
            document.getElementById('mainImage').src = element.src;
            document.querySelectorAll('.galeria-productos .img-thumbnail').forEach(item => {
                item.classList.remove('active');
            });
            element.classList.add('active');
        }
    </script>
</main>