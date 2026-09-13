<?php

require_once "auth.php";
proteger_admin();

require_once "../includes/conexion.php";

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$modo_edicion = $id > 0;

$cliente_actual = [
    "id" => 0,
    "nombre" => "",
    "logo" => "",
    "web" => "",
    "orden" => 0,
    "activo" => 1
];

if ($modo_edicion) {
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = :id");
    $stmt->execute([":id" => $id]);
    $cliente_encontrado = $stmt->fetch();

    if ($cliente_encontrado) {
        $cliente_actual = $cliente_encontrado;
    } else {
        header("Location: clientes.php");
        exit;
    }
}

$stmt = $pdo->query("SELECT * FROM clientes ORDER BY orden ASC, id ASC");
$clientes = $stmt->fetchAll();

$mensaje_ok = isset($_GET["ok"]);
$mensaje_error = $_GET["error"] ?? "";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar clientes | Zángano Pictures 360</title>
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
        <h1>Clientes</h1>
        <p>Gestiona los logos y nombres que aparecen en la sección de clientes.</p>
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
                <h2>Listado de clientes</h2>
                <a href="clientes.php" class="admin-action-link">Nuevo cliente</a>
            </div>

            <?php if (empty($clientes)): ?>
                <div class="admin-empty">
                    <h2>No hay clientes creados</h2>
                    <p>Crea el primer cliente desde el formulario.</p>
                </div>
            <?php else: ?>

                <div class="admin-services-list">
                    <?php foreach ($clientes as $cliente): ?>
                        <article class="admin-service-item">
                            <div class="admin-service-image">
                                <?php 
                                    $logo = $cliente["logo"] ?? "";
                                    $ruta_logo = $logo !== "" ? "../" . $logo : "";
                                ?>

                                <?php if ($logo !== ""): ?>
                                    <img src="<?= limpiar($ruta_logo) ?>" alt="<?= limpiar($cliente["nombre"]) ?>">
                                <?php else: ?>
                                    <span><?= limpiar($cliente["nombre"]) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="admin-service-info">
                                <h3><?= limpiar($cliente["nombre"]) ?></h3>
                                <p><?= limpiar($cliente["web"]) ?: "Sin enlace web" ?></p>

                                <div class="admin-service-meta">
                                    <span>Orden: <?= (int)$cliente["orden"] ?></span>

                                    <?php if ((int)$cliente["activo"] === 1): ?>
                                        <span class="status-badge status-respondido">Visible</span>
                                    <?php else: ?>
                                        <span class="status-badge status-leido">Oculto</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="admin-service-actions">
                                <a href="clientes.php?id=<?= (int)$cliente["id"] ?>" class="admin-action-link">
                                    Editar
                                </a>

                                <form action="eliminar_cliente.php" method="post" onsubmit="return confirm('¿Seguro que quieres eliminar este cliente?');">
                                    <input type="hidden" name="csrf_token" value="<?= limpiar($_SESSION["csrf_token"]) ?>">
                                    <input type="hidden" name="id" value="<?= (int)$cliente["id"] ?>">

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
                <h2><?= $modo_edicion ? "Editar cliente" : "Crear cliente" ?></h2>
            </div>

            <form action="guardar_cliente.php" method="post" class="admin-service-form">
                <input type="hidden" name="csrf_token" value="<?= limpiar($_SESSION["csrf_token"]) ?>">
                <input type="hidden" name="id" value="<?= (int)$cliente_actual["id"] ?>">

                <label>Nombre del cliente</label>
                <input 
                    type="text" 
                    name="nombre" 
                    value="<?= limpiar($cliente_actual["nombre"]) ?>" 
                    placeholder="Ejemplo: Diario Jaén"
                    required
                >

                <label>Ruta del logo</label>
                <input 
                    type="text" 
                    name="logo" 
                    value="<?= limpiar($cliente_actual["logo"]) ?>" 
                    placeholder="assets/img/clientes/diario-jaen.png"
                >

                <label>Web del cliente</label>
                <input 
                    type="url" 
                    name="web" 
                    value="<?= limpiar($cliente_actual["web"]) ?>" 
                    placeholder="https://..."
                >

                <label>Orden</label>
                <input 
                    type="number" 
                    name="orden" 
                    value="<?= (int)$cliente_actual["orden"] ?>" 
                    min="0"
                >

                <label class="checkbox-line">
                    <input 
                        type="checkbox" 
                        name="activo" 
                        value="1"
                        <?= (int)$cliente_actual["activo"] === 1 ? "checked" : "" ?>
                    >
                    <span>Mostrar este cliente en la web</span>
                </label>

                <button type="submit" class="btn btn-primary">
                    <?= $modo_edicion ? "Guardar cambios" : "Crear cliente" ?>
                </button>
            </form>
        </div>

    </section>

</main>

</body>
</html>
