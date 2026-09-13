<?php
include "includes/header.php";
require_once "includes/conexion.php";

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

$stmt = $pdo->prepare("SELECT * FROM noticias WHERE id = :id AND activo = 1 LIMIT 1");
$stmt->execute([":id" => $id]);
$noticia = $stmt->fetch();

if (!$noticia) {
    header("Location: noticias.php");
    exit;
}
?>

<main>

    <section class="page-hero news-detail-hero">
        <div class="container">
            <span class="eyebrow">Noticia</span>
            <h1><?= htmlspecialchars($noticia["titulo"]) ?></h1>
            <p><?= date("d/m/Y", strtotime($noticia["fecha_publicacion"])) ?></p>
        </div>
    </section>

    <section class="section news-detail-section">
        <div class="container news-detail-container">

            <?php if (!empty($noticia["imagen"]) && file_exists(__DIR__ . "/" . $noticia["imagen"])): ?>
                <div class="news-detail-image">
                    <img src="<?= htmlspecialchars($noticia["imagen"]) ?>" alt="<?= htmlspecialchars($noticia["titulo"]) ?>">
                </div>
            <?php endif; ?>

            <article class="news-detail-card">
                <?php if (!empty($noticia["resumen"])): ?>
                    <p class="news-detail-summary">
                        <?= htmlspecialchars($noticia["resumen"]) ?>
                    </p>
                <?php endif; ?>

                <div class="news-detail-text">
                    <?= nl2br(htmlspecialchars($noticia["contenido"])) ?>
                </div>

                <div class="news-detail-actions">
                    <a href="noticias.php" class="btn btn-secondary">← Volver a noticias</a>
                    <a href="contacto.php" class="btn btn-primary">Contactar</a>
                </div>
            </article>

        </div>
    </section>

</main>

<?php include "includes/footer.php"; ?>