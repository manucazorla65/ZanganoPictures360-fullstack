<?php

require_once "auth.php";
proteger_admin();

require_once "../includes/conexion.php";

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$modo_edicion = $id > 0;

$noticia_actual = [
    "id" => 0,
    "titulo" => "",
    "resumen" => "",
    "contenido" => "",
    "imagen" => "",
    "orden" => 0,
    "activo" => 1
];

if ($modo_edicion) {
    $stmt = $pdo->prepare("SELECT * FROM noticias WHERE id = :id");
    $stmt->execute([":id" => $id]);
    $noticia_encontrada = $stmt->fetch();

    if ($noticia_encontrada) {
        $noticia_actual = $noticia_encontrada;
    } else {
        header("Location: noticias.php");
        exit;
    }
}

$stmt = $pdo->query("SELECT * FROM noticias ORDER BY orden ASC, fecha_publicacion DESC, id DESC");
$noticias = $stmt->fetchAll();

$mensaje_ok = isset($_GET["ok"]);
$mensaje_error = $_GET["error"] ?? "";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar noticias | Zángano Pictures 360</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/css/style.css?v=8">
</head>
<body>

<header class="admin-header">
    <div class="admin-header-content">
        <a href="../index.php" class="admin-logo">
            <img src="../assets/img/logo.png?v=2" alt="Zángano Pictures 360">
        </a>

        <nav class="admin-nav">
            <a href="panel.php">Mensajes</a>
            <a href="servicios.php">Servicios</a>
            <a href="noticias.php">Noticias</a>
            <a href="clientes.php">Clientes</a>
            <a href="resenas.php">Reseñas</a>
            <a href="../index.php" target="_blank">Ver web</a>
            <a href="logout.php" class="admin-logout">Cerrar sesión</a>
        </nav>
    </div>
</header>

<main class="admin-main">

    <section class="admin-title-section">
        <span class="eyebrow">Contenido editable</span>
        <h1>Noticias</h1>
        <p>Gestiona las noticias que aparecerán en la página pública de actualidad.</p>
    </section>

    <?php if ($mensaje_ok): ?>
        <div class="alert alert-success admin-alert-center">
            Los cambios se han guardado correctamente.
        </div>
    <?php endif; ?>

    <?php if ($mensaje_error !== ""): ?>
        <div class="alert alert-error admin-alert-center">
            <?= limpiar($mensaje_error) ?>
        </div>
    <?php endif; ?>

    <section class="admin-services-layout">

        <div class="admin-panel-card">
            <div class="admin-section-heading">
                <h2>Listado de noticias</h2>
                <a href="noticias.php" class="admin-action-link">Nueva noticia</a>
            </div>

            <?php if (empty($noticias)): ?>
                <div class="admin-empty">
                    <h2>No hay noticias creadas</h2>
                    <p>Crea la primera noticia desde el formulario.</p>
                </div>
            <?php else: ?>

                <div class="admin-services-list">
                    <?php foreach ($noticias as $noticia): ?>
                        <article class="admin-service-item">
                            <div class="admin-service-image">
                                <?php if (!empty($noticia["imagen"])): ?>
                                    <img src="../<?= limpiar($noticia["imagen"]) ?>" alt="<?= limpiar($noticia["titulo"]) ?>">
                                <?php else: ?>
                                    <span>360</span>
                                <?php endif; ?>
                            </div>

                            <div class="admin-service-info">
                                <h3><?= limpiar($noticia["titulo"]) ?></h3>
                                <p><?= limpiar($noticia["resumen"]) ?: "Sin resumen" ?></p>

                                <div class="admin-service-meta">
                                    <span>Orden: <?= (int)$noticia["orden"] ?></span>
                                    <span><?= date("d/m/Y", strtotime($noticia["fecha_publicacion"])) ?></span>

                                    <?php if ((int)$noticia["activo"] === 1): ?>
                                        <span class="status-badge status-respondido">Visible</span>
                                    <?php else: ?>
                                        <span class="status-badge status-leido">Oculta</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="admin-service-actions">
                                <a href="noticias.php?id=<?= (int)$noticia["id"] ?>" class="admin-action-link">
                                    Editar
                                </a>

                                <form action="eliminar_noticia.php" method="post" onsubmit="return confirm('¿Seguro que quieres eliminar esta noticia?');">
                                    <input type="hidden" name="csrf_token" value="<?= limpiar($_SESSION["csrf_token"]) ?>">
                                    <input type="hidden" name="id" value="<?= (int)$noticia["id"] ?>">

                                    <button type="submit" class="admin-action-button danger">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>
        </div>

        <div class="admin-panel-card">
            <div class="admin-section-heading">
                <h2><?= $modo_edicion ? "Editar noticia" : "Crear noticia" ?></h2>
            </div>

            <form action="guardar_noticia.php" method="post" enctype="multipart/form-data" class="admin-service-form">
                <input type="hidden" name="csrf_token" value="<?= limpiar($_SESSION["csrf_token"]) ?>">
                <input type="hidden" name="id" value="<?= (int)$noticia_actual["id"] ?>">
                <input type="hidden" name="imagen_actual" value="<?= limpiar($noticia_actual["imagen"]) ?>">

                <label>Título</label>
                <input 
                    type="text" 
                    name="titulo" 
                    value="<?= limpiar($noticia_actual["titulo"]) ?>" 
                    placeholder="Ejemplo: Nuevo tour virtual realizado"
                    required
                >

                <label>Resumen</label>
                <textarea 
                    name="resumen" 
                    placeholder="Texto breve que aparecerá en el listado"
                ><?= limpiar($noticia_actual["resumen"]) ?></textarea>

                <label>Contenido completo</label>
                <textarea 
                    name="contenido" 
                    placeholder="Texto completo de la noticia"
                    required
                ><?= limpiar($noticia_actual["contenido"]) ?></textarea>

                <label>Imagen de la noticia</label>
                <input type="file" name="imagen" accept="image/jpeg,image/png,image/webp">

                <?php if (!empty($noticia_actual["imagen"])): ?>
                    <p class="admin-current-image">
                        Imagen actual: <?= limpiar($noticia_actual["imagen"]) ?>
                    </p>
                <?php endif; ?>

                <label>Orden</label>
                <input 
                    type="number" 
                    name="orden" 
                    value="<?= (int)$noticia_actual["orden"] ?>" 
                    min="0"
                >

                <label class="checkbox-line">
                    <input 
                        type="checkbox" 
                        name="activo" 
                        value="1"
                        <?= (int)$noticia_actual["activo"] === 1 ? "checked" : "" ?>
                    >
                    <span>Mostrar esta noticia en la web</span>
                </label>

                <button type="submit" class="btn btn-primary">
                    <?= $modo_edicion ? "Guardar cambios" : "Crear noticia" ?>
                </button>
            </form>
        </div>

    </section>

</main>

</body>
</html>