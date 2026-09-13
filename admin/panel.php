<?php

require_once "auth.php";
proteger_admin();

require_once "../includes/conexion.php";

$total_mensajes = $pdo->query("SELECT COUNT(*) FROM mensajes_contacto")->fetchColumn();
$total_nuevos = $pdo->query("SELECT COUNT(*) FROM mensajes_contacto WHERE estado = 'nuevo'")->fetchColumn();
$total_leidos = $pdo->query("SELECT COUNT(*) FROM mensajes_contacto WHERE estado = 'leido'")->fetchColumn();
$total_respondidos = $pdo->query("SELECT COUNT(*) FROM mensajes_contacto WHERE estado = 'respondido'")->fetchColumn();

$stmt = $pdo->query("SELECT * FROM mensajes_contacto ORDER BY fecha DESC");
$mensajes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de administración | Zángano Pictures 360</title>
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
        <span class="eyebrow">Zona privada</span>
        <h1>Mensajes recibidos</h1>
        <p>Desde aquí puedes revisar las solicitudes enviadas desde el formulario de contacto.</p>
    </section>

    <section class="admin-stats">
        <div class="admin-stat-card">
            <span>Total</span>
            <strong><?= limpiar($total_mensajes) ?></strong>
        </div>

        <div class="admin-stat-card">
            <span>Nuevos</span>
            <strong><?= limpiar($total_nuevos) ?></strong>
        </div>

        <div class="admin-stat-card">
            <span>Leídos</span>
            <strong><?= limpiar($total_leidos) ?></strong>
        </div>

        <div class="admin-stat-card">
            <span>Respondidos</span>
            <strong><?= limpiar($total_respondidos) ?></strong>
        </div>
    </section>

    <section class="admin-panel-card">

        <?php if (empty($mensajes)): ?>
            <div class="admin-empty">
                <h2>Aún no hay mensajes</h2>
                <p>Cuando alguien contacte desde la web, aparecerá aquí.</p>
            </div>
        <?php else: ?>

            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Estado</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Servicio</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($mensajes as $mensaje): ?>
                            <tr>
                                <td>
                                    <span class="status-badge status-<?= limpiar($mensaje["estado"]) ?>">
                                        <?= limpiar($mensaje["estado"]) ?>
                                    </span>
                                </td>

                                <td><?= limpiar($mensaje["nombre"]) ?></td>
                                <td><?= limpiar($mensaje["email"]) ?></td>
                                <td><?= limpiar($mensaje["servicio_interes"]) ?: "Sin especificar" ?></td>
                                <td><?= date("d/m/Y H:i", strtotime($mensaje["fecha"])) ?></td>

                                <td class="admin-actions">
                                    
                                    <form action="actualizar_mensaje.php" method="post">
                                        <input type="hidden" name="csrf_token" value="<?= limpiar($_SESSION["csrf_token"]) ?>">
                                        <input type="hidden" name="id" value="<?= (int)$mensaje["id"] ?>">
                                        <input type="hidden" name="accion" value="respondido">

                                        <button type="submit" class="admin-action-button">
                                            Respondido
                                        </button>
                                    </form>

                                    <form action="actualizar_mensaje.php" method="post" onsubmit="return confirm('¿Seguro que quieres eliminar este mensaje?');">
                                        <input type="hidden" name="csrf_token" value="<?= limpiar($_SESSION["csrf_token"]) ?>">
                                        <input type="hidden" name="id" value="<?= (int)$mensaje["id"] ?>">
                                        <input type="hidden" name="accion" value="eliminar">

                                        <button type="submit" class="admin-action-button danger">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>

    </section>

</main>

</body>
</html>