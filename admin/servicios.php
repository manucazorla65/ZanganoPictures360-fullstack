<?php

require_once "auth.php";
proteger_admin();

require_once "../includes/conexion.php";

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$modo_edicion = $id > 0;

$servicio_actual = [
    "id" => 0,
    "titulo" => "",
    "descripcion_corta" => "",
    "descripcion_larga" => "",
    "imagen" => "",
    "orden" => 0,
    "activo" => 1
];

if ($modo_edicion) {
    $stmt = $pdo->prepare("SELECT * FROM servicios WHERE id = :id");
    $stmt->execute([
        ":id" => $id
    ]);

    $servicio_encontrado = $stmt->fetch();

    if ($servicio_encontrado) {
        $servicio_actual = $servicio_encontrado;
    } else {
        header("Location: servicios.php");
        exit;
    }
}

try {
    $stmt = $pdo->query("SELECT * FROM servicios ORDER BY orden ASC, id ASC");
    $servicios = $stmt->fetchAll();
} catch (PDOException $e) {
    $servicios = [];
}

$mensaje_ok = isset($_GET["ok"]);
$mensaje_error = $_GET["error"] ?? "";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Servicios | Panel de administración</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/css/style.css?v=13">
</head>
<body>

<header class="admin-header">
    <div class="admin-header-content">
        <a href="../index.php" class="admin-logo">
            <img src="../assets/img/logo.png?v=3" alt="Zángano Pictures 360">
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
        <h1>Servicios</h1>
        <p>
            Desde aquí puedes modificar, crear o eliminar los servicios que aparecen en la página principal y en sus páginas individuales.
        </p>
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
                <h2>Listado de servicios</h2>
                <a href="servicios.php" class="admin-action-link">Nuevo servicio</a>
            </div>

            <?php if (empty($servicios)): ?>

                <div class="admin-empty">
                    <h2>No hay servicios creados</h2>
                    <p>Crea el primer servicio desde el formulario.</p>
                </div>

            <?php else: ?>

                <div class="admin-services-list">
                    <?php foreach ($servicios as $servicio): ?>
                        <article class="admin-service-item">

                            <div class="admin-service-image">
                                <?php if (!empty($servicio["imagen"])): ?>
                                    <img src="../<?= limpiar($servicio["imagen"]) ?>" alt="<?= limpiar($servicio["titulo"]) ?>">
                                <?php else: ?>
                                    <span>360º</span>
                                <?php endif; ?>
                            </div>

                            <div class="admin-service-info">
                                <h3><?= limpiar($servicio["titulo"] ?? "") ?></h3>

                                <p>
                                    <?= limpiar(strip_tags($servicio["descripcion_corta"] ?? "")) ?>
                                </p>

                                <div class="admin-service-meta">
                                    <span>Orden: <?= (int)($servicio["orden"] ?? 0) ?></span>

                                    <?php if ((int)($servicio["activo"] ?? 0) === 1): ?>
                                        <span class="status-badge status-respondido">Visible</span>
                                    <?php else: ?>
                                        <span class="status-badge status-leido">Oculto</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="admin-service-actions">
                                <a href="servicios.php?id=<?= (int)$servicio["id"] ?>" class="admin-action-link">
                                    Editar
                                </a>

                                <form action="eliminar_servicio.php" method="post" onsubmit="return confirm('¿Seguro que quieres eliminar este servicio?');">
                                    <input type="hidden" name="csrf_token" value="<?= limpiar($_SESSION["csrf_token"]) ?>">
                                    <input type="hidden" name="id" value="<?= (int)$servicio["id"] ?>">

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
                <h2><?= $modo_edicion ? "Editar servicio" : "Crear servicio" ?></h2>
            </div>

            <form action="guardar_servicio.php" method="post" class="admin-service-form">
                <input type="hidden" name="csrf_token" value="<?= limpiar($_SESSION["csrf_token"]) ?>">
                <input type="hidden" name="id" value="<?= (int)$servicio_actual["id"] ?>">

                <label>Título</label>
                <input 
                    type="text" 
                    name="titulo" 
                    value="<?= limpiar($servicio_actual["titulo"]) ?>" 
                    placeholder="Ejemplo: Tour Virtual"
                    required
                >

                <label>Descripción corta</label>

                <textarea 
                    id="descripcion_corta"
                    name="descripcion_corta" 
                    placeholder="Texto breve que aparece en las tarjetas. No uses negrita ni cursiva aquí."
                    required
                ><?= limpiar(strip_tags($servicio_actual["descripcion_corta"])) ?></textarea>

                <label>Descripción larga</label>

                <div class="admin-editor-toolbar">
                    <button type="button" data-target="descripcion_larga" data-tag="strong">B</button>
                    <button type="button" data-target="descripcion_larga" data-tag="em"><em>I</em></button>
                </div>

                <textarea 
                    id="descripcion_larga"
                    name="descripcion_larga" 
                    placeholder="Texto completo. Separa los párrafos dejando una línea entre ellos."
                    required
                ><?= limpiar($servicio_actual["descripcion_larga"]) ?></textarea>

                <label>Imagen</label>
                <input 
                    type="text" 
                    name="imagen" 
                    value="<?= limpiar($servicio_actual["imagen"]) ?>" 
                    placeholder="Ejemplo: assets/img/servicios/tour-virtual.png"
                    required
                >

                <label>Orden</label>
                <input 
                    type="number" 
                    name="orden" 
                    value="<?= (int)$servicio_actual["orden"] ?>" 
                    min="0"
                >

                <label class="checkbox-line">
                    <input 
                        type="checkbox" 
                        name="activo" 
                        value="1"
                        <?= (int)$servicio_actual["activo"] === 1 ? "checked" : "" ?>
                    >
                    <span>Mostrar este servicio en la web</span>
                </label>

                <button type="submit" class="btn btn-primary">
                    <?= $modo_edicion ? "Guardar cambios" : "Crear servicio" ?>
                </button>
            </form>
        </div>

    </section>

</main>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const botones = document.querySelectorAll(".admin-editor-toolbar button");

    botones.forEach(function (boton) {
        boton.addEventListener("click", function () {
            const targetId = boton.dataset.target;
            const tag = boton.dataset.tag;
            const textarea = document.getElementById(targetId);

            if (!textarea) {
                return;
            }

            const inicio = textarea.selectionStart;
            const fin = textarea.selectionEnd;
            const texto = textarea.value;
            const seleccionado = texto.substring(inicio, fin);

            const apertura = "<" + tag + ">";
            const cierre = "</" + tag + ">";

            let nuevoTexto;

            if (seleccionado.length > 0) {
                nuevoTexto = texto.substring(0, inicio) + apertura + seleccionado + cierre + texto.substring(fin);
                textarea.value = nuevoTexto;
                textarea.focus();
                textarea.selectionStart = inicio + apertura.length;
                textarea.selectionEnd = fin + apertura.length;
            } else {
                nuevoTexto = texto.substring(0, inicio) + apertura + cierre + texto.substring(fin);
                textarea.value = nuevoTexto;
                textarea.focus();
                textarea.selectionStart = inicio + apertura.length;
                textarea.selectionEnd = inicio + apertura.length;
            }
        });
    });
});
</script>

</body>
</html>