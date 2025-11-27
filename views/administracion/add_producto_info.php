<section class="container my-5">
    <h2>Agregar Información Adicional</h2>

    <form action="actions/producto/crear_producto_datos_acc.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="producto_id" value="<?= $producto_id ?>">
        <fieldset class="mb-4">
            <legend>Datos Técnicos</legend>
            <div id="datos-tecnicos-container">
                <div class="row mb-2 dato-tecnico">
                    <div class="col-5">
                        <label for="datos_nombre[]">Nombre</label>
                        <input type="text" name="datos_nombre[]" class="form-control" placeholder="Altura">
                    </div>
                    <div class="col-6">
                        <label for="datos_nombre[]">Valor</label>
                        <input type="text" name="datos_valor[]" class="form-control" placeholder="18 metros">
                    </div>
                    <div class="col-1"></div>
                </div>
            </div>
            <button type="button" id="agregar-dato" class="btn btn-warning btn-sm mt-2">Agregar otro dato</button>
        </fieldset>
        <fieldset class="mb-4">
            <legend>Imágenes</legend>
            <div id="imagenes-container">
                <div class="mb-2 imagen-item">
                    <input type="file" name="imagenes[]" class="form-control" accept=".webp,.jpg,.png">
                </div>
            </div>
            <button type="button" id="agregar-imagen" class="btn btn-secondary btn-sm mt-2">Agregar otra imagen</button>
        </fieldset>

        <button type="submit" class="btn btn btn-kitchenpro btn-warning">Guardar Información Adicional</button>
    </form>
</section>

<script>
    document.getElementById('agregar-dato').addEventListener('click', function() {
        const container = document.getElementById('datos-tecnicos-container');
        const div = document.createElement('div');
        div.classList.add('row', 'mb-2', 'dato-tecnico');
        div.innerHTML = `
            <div class="row mb-2 dato-tecnico">
            <div class="col-5">
                <input type="text" name="datos_nombre[]" class="form-control" placeholder="Altura">
            </div>
            <div class="col-6">
                <input type="text" name="datos_valor[]" class="form-control" placeholder="18 metros">
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-danger w-100">Eliminar</button>
            </div>
        </div>
        `;
        container.appendChild(div);
    });

    document.addEventListener('click', function(e) {
        if(e.target && e.target.classList.contains('eliminar-dato')) {
            e.target.closest('.dato-tecnico').remove();
        }
    });

    document.getElementById('agregar-imagen').addEventListener('click', function() {
        const container = document.getElementById('imagenes-container');
        const div = document.createElement('div');
        div.classList.add('mb-2', 'imagen-item');
        div.innerHTML = `<input type="file" name="imagenes[]" class="form-control" accept=".webp,.jpg,.png">`;
        container.appendChild(div);
    });
</script>
