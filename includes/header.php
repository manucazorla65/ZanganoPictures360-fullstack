<?php
require_once __DIR__ . "/config.php";

$servicios_nav = [];

try {
    require_once __DIR__ . "/conexion.php";

    $stmt_nav = $pdo->query("SELECT id, titulo FROM servicios WHERE activo = 1 ORDER BY orden ASC, id ASC");
    $servicios_nav = $stmt_nav->fetchAll();
} catch (Exception $e) {
    $servicios_nav = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site_name) ?></title>

    <!-- Visor panorámico 360 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css">
    <script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>

    <!-- Estilos principales -->
    <link rel="stylesheet" href="assets/css/style.css?v=20">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>
<body>

<header class="top-header compact-header" id="topHeader">
    <div class="compact-header-container">

        <div class="compact-logo-area">
            <a href="index.php">
                <img src="assets/img/logo.png?v=3" alt="Logo Zángano Pictures 360" class="compact-logo">
            </a>
        </div>

        <nav class="compact-navbar">
            <button class="menu-toggle" id="menuToggle" aria-label="Abrir menú">
                ☰
            </button>

            <ul class="nav-menu compact-nav-menu" id="navMenu">
                <li><a href="index.php">Inicio</a></li>

                <li class="nav-dropdown">
                    <button type="button" class="nav-dropdown-button">
                        Servicios <span>⌄</span>
                    </button>

                    <ul class="dropdown-menu">
                        <?php foreach ($servicios_nav as $servicio_nav): ?>
                            <li>
                                <a href="servicio.php?id=<?= (int)$servicio_nav["id"] ?>">
                                    <?= htmlspecialchars($servicio_nav["titulo"]) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>

                <li><a href="noticias.php">Noticias</a></li>
                <li><a href="contacto.php">Contacto</a></li>
            </ul>
        </nav>

    </div>
</header>