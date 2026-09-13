<?php
include "includes/header.php";
require_once "includes/conexion.php";

try {
    $stmt = $pdo->query("SELECT * FROM servicios WHERE activo = 1 ORDER BY orden ASC, id ASC");
    $servicios_publicos = $stmt->fetchAll();
} catch (PDOException $e) {
    $servicios_publicos = [];
}
?>

<main>

    <section class="page-hero services-page-hero">
        <div class="container">
            <span class="eyebrow">Soluciones 360º</span>
            <h1>Servicios</h1>
            <p>
                Descubre las soluciones visuales e inmersivas que ofrecemos para empresas, turismo, eventos y espacios únicos.
            </p>
        </div>
    </section>

    <section class="services section bg-soft" id="servicios">
        <div class="container">
            <h2 class="section-title">Todos nuestros servicios</h2>
            <p class="section-subtitle">
                Cada servicio está pensado para mostrar espacios, proyectos y experiencias de una forma moderna, interactiva y profesional.
            </p>

            <?php if (empty($servicios_publicos)): ?>

                <div class="news-empty">
                    <h2>No hay servicios disponibles</h2>
                    <p>Pronto añadiremos nuevos servicios.</p>
                </div>

            <?php else: ?>

                <div class="services-grid">
                    <?php foreach ($servicios_publicos as $servicio): ?>
                        <a href="servicio.php?id=<?= (int)$servicio["id"] ?>" class="home-service-card">

                            <div class="home-service-media">
                                <?php if (!empty($servicio["imagen"])): ?>
                                    <img src="<?= htmlspecialchars($servicio["imagen"]) ?>" alt="<?= htmlspecialchars($servicio["titulo"]) ?>">
                                <?php else: ?>
                                    <span>360º</span>
                                <?php endif; ?>
                            </div>

                            <div class="home-service-content">
                                <h3><?= htmlspecialchars($servicio["titulo"]) ?></h3>
                                <p><?= htmlspecialchars($servicio["descripcion_corta"]) ?></p>
                                <span>Explorar servicio →</span>
                            </div>

                        </a>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>
        </div>
    </section>

</main>

<?php include "includes/footer.php"; ?>