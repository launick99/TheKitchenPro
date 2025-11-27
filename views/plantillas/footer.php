<footer class="text-center text-lg-start text-dark" style="background-color:#ffffe9">
    <div class="bg-primary">
        <section class="d-flex flex-column flex-md-row justify-content-between p-4 text-white container">
            <div class="me-5">
                <h2>Siguenos en nuestras redes!</h2>
            </div>
            <div>
                <a href="https://www.instagram.com/cute.cats.cure/" target="_blank" class="text-white me-4">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://www.facebook.com/groups/538474476739820" target="_blank" class="text-white me-4">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://x.com/PepitoTheCat" class="text-white me-4" target="_blank">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://doodles.google/doodle/tamas-18th-birthday/" target="_blank" class="text-white me-4">
                    <i class="fab fa-google"></i>
                </a>
            </div>
        </section>
    </div>
    <section class="links mb-4">
        <div class="container text-center text-md-start mt-5 ">
            <div class="row mt-3">
                <div class="col-md-6 col-lg-4 mx-auto mb-md-0 mb-4">
                    <p class="h6 text-uppercase fw-bold">The Kitchen Pro</p>
                    <hr>
                    <p>
                        La cocina que amas tener, la cocina que siempre soñaste tener.
                    </p>
                </div>
                <div class="col-md-6 col-lg-4 mx-auto mb-md-0 mb-4">
                    <h2 class="text-uppercase fw-bold h6">Productos</h2>
                    <hr>
                    <?php foreach(Categorias::getTodas() as $c => $categoria){ ?>
                    <?php if($c >= 5){ break; } ?>
                        <p>
                            <a href="?section=categoria&categoria=<?= $categoria->getId() ?>" class="text-dark"><?= $categoria->getNombre() ?></a>
                        </p>
                    <?php } ?>
                </div>
                <div class="col-md-6 col-lg-4 mx-auto mb-md-0 mb-4">
                    <h2 class="h6 text-uppercase fw-bold">Contacto</h2>
                    <hr>
                    <p>
                        <i class="fas fa-home mr-3"></i>
                        <a href="https://maps.app.goo.gl/DwpMkj7S8PfrK3Ey7" target="_blank" class="text-dark"> Av. ficticia 1234, La Pampa</a>
                    </p>
                    <p>
                        <i class="fas fa-envelope mr-3"></i>
                        <a href="mailto:nose@soyuntest.gob.ar" class="text-dark"> nose@soyuntest.gob.ar</a>
                    </p>
                    <p>
                        <i class="fas fa-phone mr-3"></i>
                        <a href="tel:5491166669998"  class="text-dark"> +54 9 11 6666 9998</a>
                    </p>
                    <p>
                        <i class="fas fa-print mr-3"></i>
                        <a href="tel:5491166669999" class="text-dark">+54 9 11 6666 9999</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
</footer>