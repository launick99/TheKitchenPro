<?php

?>
<form action="actions/procesar_producto.php" method="POST">
    <div class="mb-3">
        <label for="nombre_producto" class="form-label">Nombre del producto</label>
        <input type="text" class="form-control" id="nombre_producto" name="nombre" required>
    </div>
    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
    </div>
    <div class="mb-3">
        <label for="precio" class="form-label">Precio</label>
        <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
    </div>
    <div class="mb-3">
        <label for="stock" class="form-label">Stock</label>
        <input type="number" class="form-control" id="stock" name="stock" required>
    </div>
    <div class="mb-3">
        <label for="categoria_id" class="form-label">Categoría</label>
        <select class="form-select" id="categoria_id" name="categoria_id" required>
            <option value="">Seleccionar</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="imagen" class="form-label">URL de la imagen</label>
        <input type="text" class="form-control" id="imagen" name="imagen">
    </div>
    <button type="submit" class="btn btn-success">Agregar Producto</button>
</form>