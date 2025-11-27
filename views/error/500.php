<div class="container my-5">
    <div class="text-center my-2">
        <h2>
            500 - Error interno del servidor
        </h2>
        <img class="rounded mx-auto d-block" style="max-width: 256px;" src="./assets/img/500.webp" alt="error 500 con tostadita triste">
        <p class="h3">
            <?= $error->getMessage()  ?>
            <br>
            hm.. supongo que no aprobe la materia si sale esto..
        </p>
    </div>
</div>