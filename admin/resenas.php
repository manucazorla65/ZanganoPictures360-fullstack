<?php

require_once "auth.php";
proteger_admin();

require_once "../includes/conexion.php";

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$modo_edicion = $id > 0;

$resena_actual = [
    "id" => 0,
    "titulo" => "",
    "comentario" => "",
    "nombre" => "",
    "cargo" => "",
    "foto" => "",
    "estrellas" => 5,
    "orden" => 0,
    "activo" => 1
];

if ($modo_edicion) {
    $stmt = $pdo->prepare("SELECT * FROM resenas WHERE id = :id");
    $stmt->execute([":id" => $id]);
    $resena_encontrada = $stmt->fetch();

    if ($resena_encontrada) {
        $resena_actual = $resena_encontrada;
    } else {
        header("Location: resenas.php");
        exit;
    }
}

$stmt = $pdo->query("SELECT * FROM resenas ORDER BY orden ASC, id ASC");
$resenas = $stmt->fetchAll();

$mensaje_ok = isset($_GET["ok"]);
$mensaje_error = $_GET["error"] ?? "";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar reseñas | Zángano Pictures 360</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/css/style.css?v=6">
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
        <h1>Reseñas</h1>
        <p>Gestiona los testimonios que aparecen en la página principal.</p>
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
                <h2>Listado de reseñas</h2>
                <a href="resenas.php" class="admin-action-link">Nueva reseña</a>
            </div>

            <?php if (empty($resenas)): ?>
                <div class="admin-empty">
                    <h2>No hay reseñas creadas</h2>
                    <p>Crea la primera reseña desde el formulario.</p>
                </div>
            <?php else: ?>

                <div class="admin-services-list">
                    <?php foreach ($resenas as $resena): ?>
                        <article class="admin-service-item">
                            <div class="admin-service-image">
                                <?php 
                                    $foto = $resena["foto"] ?? "";
                                    $ruta_foto = $foto !== "" ? "../" . $foto : "";
                                    $inicial = !empty($resena["nombre"]) ? strtoupper(substr($resena["nombre"], 0, 1)) : "R";
                                ?>

                                <?php if ($foto !== ""): ?>
                                    <img src="<?= limpiar($ruta_foto) ?>" alt="<?= limpiar($resena["nombre"]) ?>">
                                <?php else: ?>
                                    <span><?= limpiar($inicial) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="admin-service-info">
                                <h3><?= limpiar($resena["nombre"]) ?></h3>
                                <p><?= limpiar($resena["titulo"]) ?></p>

                                <div class="admin-service-meta">
                                    <span>Orden: <?= (int)$resena["orden"] ?></span>
                                    <span>Estrellas: <?= (int)$resena["estrellas"] ?></span>

                                    <?php if ((int)$resena["activo"] === 1): ?>
                                        <span class="status-badge status-respondido">Visible</span>
                                    <?php else: ?>
                                        <span class="status-badge status-leido">Oculta</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="admin-service-actions">
                                <a href="resenas.php?id=<?= (int)$resena["id"] ?>" class="admin-action-link">
                                    Editar
                                </a>

                                <form action="eliminar_resena.php" method="post" onsubmit="return confirm('¿Seguro que quieres eliminar esta reseña?');">
                                    <input type="hidden" name="csrf_token" value="<?= limpiar($_SESSION["csrf_token"]) ?>">
                                    <input type="hidden" name="id" value="<?= (int)$resena["id"] ?>">

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
                <h2><?= $modo_edicion ? "Editar reseña" : "Crear reseña" ?></h2>
            </div>

            <form action="guardar_resena.php" method="post" class="admin-service-form">
                <input type="hidden" name="csrf_token" value="<?= limpiar($_SESSION["csrf_token"]) ?>">
                <input type="hidden" name="id" value="<?= (int)$resena_actual["id"] ?>">

                <label>Título de la reseña</label>
                <input 
                    type="text" 
                    name="titulo" 
                    value="<?= limpiar($resena_actual["titulo"]) ?>" 
                    placeholder="Ejemplo: Profesionalismo, creatividad y habilidad"
                    required
                >

                <label>Comentario</label>
                <textarea 
                    name="comentario" 
                    placeholder="Texto completo de la reseña"
                    required
                ><?= limpiar($resena_actual["comentario"]) ?></textarea>

                <label>Nombre</label>
                <input 
                    type="text" 
                    name="nombre" 
                    value="<?= limpiar($resena_actual["nombre"]) ?>" 
                    placeholder="Ejemplo: Elizabeth García"
                    required
                >

                <label>Cargo o descripción</label>
                <input 
                    type="text" 
                    name="cargo" 
                    value="<?= limpiar($resena_actual["cargo"]) ?>" 
                    placeholder="Ejemplo: Directora Adjunta del Hotel Palacio de Úbeda"
                >

                <label>Ruta de la foto</label>
                <input 
                    type="text" 
                    name="foto" 
                    value="<?= limpiar($resena_actual["foto"]) ?>" 
                    placeholder="assets/img/resenas/elizabeth-garcia.jpg"
                >

                <label>Estrellas</label>
                <input 
                    type="number" 
                    name="estrellas" 
                    value="<?= (int)$resena_actual["estrellas"] ?>" 
                    min="1"
                    max="5"
                >

                <label>Orden</label>
                <input 
                    type="number" 
                    name="orden" 
                    value="<?= (int)$resena_actual["orden"] ?>" 
                    min="0"
                >

                <label class="checkbox-line">
                    <input 
                        type="checkbox" 
                        name="activo" 
                        value="1"
                        <?= (int)$resena_actual["activo"] === 1 ? "checked" : "" ?>
                    >
                    <span>Mostrar esta reseña en la web</span>
                </label>

                <button type="submit" class="btn btn-primary">
                    <?= $modo_edicion ? "Guardar cambios" : "Crear reseña" ?>
                </button>
            </form>
        </div>

    </section>

</main>

</body>
</html>
