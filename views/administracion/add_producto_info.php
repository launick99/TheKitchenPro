<?php
    $id = $_GET['id'] ?? null;
?>
<section class="container my-5">
    <h2>Agregar Información Adicional</h2>

    <form action="actions/producto/crear_producto_datos_acc.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="producto_id" value="<?= $id ?>">

        <fieldset class="mb-4">
            <legend>Datos Técnicos</legend>
            <div id="datos-tecnicos-container">
                <div class="row mb-2 dato-tecnico">
                    <div class="col-5">
                        <label for="dato_1">Nombre</label>
                        <input type="text" name="datos_nombre[]" id="dato_1" class="form-control" placeholder="Altura">
                    </div>
                    <div class="col-6">
                        <label for="valor_1">Valor</label>
                        <input type="text" name="datos_valor[]" id="valor_1" class="form-control" placeholder="18 metros">
                    </div>
                    <div class="col-1"></div>
                </div>
            </div>
            <button type="button" id="agregar-dato" class="btn btn-secondary btn-sm mt-2">Agregar otro dato</button>
        </fieldset>

        <fieldset class="mb-4">
            <legend>Imágenes</legend>
            <input type="file" name="imagenes[]" id="imagenes-input" class="form-control mb-3" accept=".webp,.jpg,.png" multiple>
            <div id="imagenes-preview" class="d-flex flex-wrap gap-2"></div>
        </fieldset>

        <button type="submit" class="btn btn btn-kitchenpro btn-warning">Guardar Información Adicional</button>
    </form>
</section>

<script>
    // DATOS TÉCNICOS
    const containerDatos = document.getElementById('datos-tecnicos-container');
    const btnAgregarDato = document.getElementById('agregar-dato');
    let idDato = 1;

    btnAgregarDato.addEventListener('click', () => {
        idDato++;
        const div = document.createElement('div');
        div.classList.add('row', 'mb-2', 'dato-tecnico');
        div.innerHTML = `
            <div class="col-5">
                <input type="text" name="datos_nombre[]" class="form-control" placeholder="Altura">
            </div>
            <div class="col-6">
                <input type="text" name="datos_valor[]" class="form-control" placeholder="18 metros">
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-danger eliminar-dato w-100">Eliminar</button>
            </div>
        `;
        containerDatos.appendChild(div);
    });

    document.addEventListener('click', e => {
        if (e.target.classList.contains('eliminar-dato')) {
            e.target.closest('.dato-tecnico').remove();
        }
    });


    // IMÁGENES
    const inputImagenes = document.getElementById('imagenes-input');
    const previewContainer = document.getElementById('imagenes-preview');

    inputImagenes.addEventListener('change', (e) => {
        previewContainer.innerHTML = '';
        const files = Array.from(e.target.files);

        files.forEach((file, index) => {
            const reader = new FileReader();
            const div = document.createElement('div');
            div.classList.add('position-relative');

            const btnEliminar = document.createElement('button');
            btnEliminar.type = 'button';
            btnEliminar.className = 'btn btn-danger btn-sm position-absolute top-0 end-0';
            btnEliminar.innerText = '×';
            btnEliminar.style.zIndex = 1;
            btnEliminar.addEventListener('click', () => {
                files.splice(index, 1);
                updateInputFiles(files);
                div.remove();
            });

            const img = document.createElement('img');
            img.className = 'img-thumbnail';
            img.style.maxHeight = '128px';

            reader.onload = e => img.src = e.target.result;
            reader.readAsDataURL(file);

            div.appendChild(img);
            div.appendChild(btnEliminar);
            previewContainer.appendChild(div);
        });
    });

    function updateInputFiles(files) {
        const dataTransfer = new DataTransfer();
        files.forEach(f => dataTransfer.items.add(f));
        inputImagenes.files = dataTransfer.files;
    }
</script>
