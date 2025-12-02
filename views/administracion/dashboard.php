<?php
    $compras = [];
?>

<section class="container my-5">
    <nav>
        <h2>Administración</h2>
        <p class="mb-5">
            Acceda a las herramientas de administración para gestionar los productos del sito.
        </p>
        <a class="btn btn-kitchenpro btn btn-warning btn btn-kitchenpro-lg" href="?section=dashboard_productos">Administrar Productos</a>
        <a class="btn btn-kitchenpro btn btn-kitchenpro-light btn btn-kitchenpro-lg mx-2" href="?section=dashboard_categorias">Administrar Categorías</a>
    </nav>
</section>
<section class="container my-5">
    <h2>Historial de Compras</h2>
    <table class="table table-responsive table-striped table-light">
        <thead class="table-warning">
            <tr>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($compras as $compra){ ?>
               <td></td>
               <td></td>
               <td></td>
            <?php } ?>
        </tbody>
    </table>
</section>
