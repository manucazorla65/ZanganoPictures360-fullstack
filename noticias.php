<?php
include "includes/header.php";
require_once "includes/conexion.php";

try {
    $stmt = $pdo->query("SELECT * FROM noticias WHERE activo = 1 ORDER BY orden ASC, fecha_publicacion DESC, id DESC");
    $noticias = $stmt->fetchAll();
} catch (PDOException $e) {
    $noticias = [];
}
?>

<main>

    <section 
        class="page-hero page-hero-with-bg"
        style="--hero-bg: url('assets/img/heroes/noticias_fondo.png?v=1');"
    >
        <div class="service-hero-bg-overlay"></div>
        <div class="service-watermark service-watermark-360">360º</div>

        <div class="container">
            <span class="eyebrow">Actualidad Zángano Pictures 360</span>
            <h1>Noticias</h1>
            <p>
                Mantente al día con las últimas novedades, proyectos, eventos y noticias de Zángano Pictures.
            </p>
        </div>
    </section>

    <section class="section news-section">
        <div class="container">

            <?php if (empty($noticias)): ?>

                <div class="news-empty">
                    <h2>Aún no hay noticias publicadas</h2>
                    <p>Pronto añadiremos novedades sobre proyectos y experiencias 360º.</p>
                </div>

            <?php else: ?>

                <div class="news-grid">
                    <?php foreach ($noticias as $noticia): ?>
                        <article class="news-card">
                            <a href="noticia.php?id=<?= (int)$noticia["id"] ?>" class="news-card-image">
                                <?php if (!empty($noticia["imagen"]) && file_exists(__DIR__ . "/" . $noticia["imagen"])): ?>
                                    <img src="<?= htmlspecialchars($noticia["imagen"]) ?>" alt="<?= htmlspecialchars($noticia["titulo"]) ?>">
                                <?php else: ?>
                                    <div class="news-image-placeholder">
                                        <span>360º</span>
                                    </div>
                                <?php endif; ?>
                            </a>

                            <div class="news-card-content">
                                <span class="news-date">
                                    <?= date("d/m/Y", strtotime($noticia["fecha_publicacion"])) ?>
                                </span>

                                <h2>
                                    <a href="noticia.php?id=<?= (int)$noticia["id"] ?>">
                                        <?= htmlspecialchars($noticia["titulo"]) ?>
                                    </a>
                                </h2>

                                <p>
                                    <?= htmlspecialchars($noticia["resumen"] ?: mb_substr($noticia["contenido"], 0, 160) . "...") ?>
                                </p>

                                <a href="noticia.php?id=<?= (int)$noticia["id"] ?>" class="news-read-more">
                                    Leer noticia →
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>

        </div>
    </section>

</main>

<?php include "includes/footer.php"; ?>