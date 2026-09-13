<?php 
include "includes/header.php"; 
include "includes/datos.php";
?>

<main>

    <section class="hero hero-carousel">
        <div class="hero-slider-track">
            <div class="hero-slider-slide">
                <img src="assets/img/hero-slider/hero-1.jpg" alt="Experiencia visual 360º">
            </div>

            <div class="hero-slider-slide">
                <img src="assets/img/hero-slider/hero-2.jpg" alt="Fotografía y vídeo 360º">
            </div>

            <div class="hero-slider-slide">
                <img src="assets/img/hero-slider/hero-3.jpg" alt="Tour virtual inmersivo">
            </div>
        </div>

        <div class="hero-content">
            <h1>Transformamos espacios en experiencias inmersivas</h1>
            <p>
                No vendemos imágenes. Rompemos las barreras del espacio, creamos experiencias virtuales e inmersivas 360° que trasladan a tu público al centro de la escena.
            </p>

            <div class="hero-buttons">
                <a href="#servicios" class="btn btn-primary">Explorar servicios</a>
                <a href="#presentacion" class="btn btn-secondary">Conocer la empresa</a>
            </div>
        </div>
    </section>

    <section class="intro section" id="presentacion">
        <div class="container intro-grid">
            <div class="intro-text">
                <h2>Zángano Pictures 360</h2>
                <h3>El mundo real no es plano. Tu comunicación tampoco debería serlo.</h3>

                <p>
                    Nacimos en Zángano Pictures con una obsesión: la tecnología solo tiene sentido si te hace sentir algo. 
                    No nos quedamos en tierra, no nos conformamos con el vídeo tradicional. 
                    Experimentamos, volamos y nos metemos en el barro para digitalizar espacios y crear realidades virtuales interactivas. 
                </p>

                <p>
                    Basándonos en una relación directa y cercana, escuchamos lo que necesitáis y lo transformamos en una experiencia inmersiva que se puede tocar, vivir y recordar, no es lo que emitimos, es lo que transmitimos. 
                    Nuestro único fin es obtener mejores los resultados ofreciendo la tecnología más innovadora. 
                </p>
                
            </div>

            <div class="intro-image intro-360-box">
                <div id="empresa360"></div>
            </div>
        </div>
    </section>

    <section class="virtual-tour-showcase section bg-soft">
        <div class="container">
            <div class="tour-showcase-grid">

                <div class="tour-showcase-text">
                    <span class="eyebrow">Ejemplo real</span>
                    <h2>Explora un tour virtual interactivo</h2>

                    <p>
                        Los tours virtuales permiten mostrar espacios reales de una forma inmersiva, profesional y accesible desde cualquier dispositivo.
                    </p>

                    <p>
                        Este ejemplo permite recorrer visualmente un espacio y entender cómo una experiencia 360º puede ayudar a presentar lugares turísticos, culturales, alojamientos, empresas o proyectos especiales.
                    </p>

                    <div class="detail-actions">
                        <a href="https://visualizador3d.com/ubeda/" target="_blank" class="btn btn-primary">
                            Abrir tour completo
                        </a>

                        <a href="contacto.php" class="btn btn-secondary">
                            Quiero algo parecido
                        </a>
                    </div>
                </div>

                <div class="tour-showcase-frame">
                    <iframe 
                        src="https://visualizador3d.com/ubeda/" 
                        title="Tour virtual interactivo de Úbeda"
                        loading="lazy"
                        allowfullscreen>
                    </iframe>
                </div>

            </div>
        </div>
    </section>

    <section class="services section bg-soft" id="servicios">
        <div class="container">
            <h2 class="section-title">Servicios que ofrecemos</h2>
            <p class="section-subtitle">
                Soluciones audiovisuales pensadas para mostrar espacios, proyectos y experiencias de una forma inmersiva, moderna y profesional.
            </p>

            <div class="services-grid">

                <?php foreach ($servicios as $id => $servicio): ?>
                    <a href="servicio.php?id=<?= $id ?>" class="home-service-card">
                        
                        <div class="home-service-media">
                            <img src="<?= htmlspecialchars($servicio["imagen"]) ?>" alt="<?= htmlspecialchars($servicio["titulo"]) ?>">
                        </div>

                        <div class="home-service-content">
                            <h3><?= htmlspecialchars($servicio["titulo"]) ?></h3>
                            <p><?= htmlspecialchars($servicio["descripcion_corta"]) ?></p>
                            <span>Explorar servicio →</span>
                        </div>

                    </a>
                <?php endforeach; ?>

            </div>
        </div>
    </section>

    <?php
$clientes_home = [];

try {
    require_once "includes/conexion.php";

    $stmt_clientes = $pdo->query("SELECT * FROM clientes WHERE activo = 1 ORDER BY orden ASC, id ASC");
    $clientes_home = $stmt_clientes->fetchAll();
} catch (Exception $e) {
    $clientes_home = [];
}

$clientes_loop = array_merge($clientes_home, $clientes_home);
?>

<section class="clients section">
    <div class="container">
        <h2 class="section-title">Clientes que han confiado en nosotros</h2>

        <?php if (empty($clientes_home)): ?>

            <div class="clients-carousel-panel">
                <p class="clients-empty-text">
                    Añade clientes desde el panel de administración para mostrarlos aquí.
                </p>
            </div>

        <?php else: ?>

            <div class="clients-carousel-panel">
                <div class="clients-carousel-track">

                    <?php foreach ($clientes_loop as $cliente): ?>
                        <?php
                            $nombre_cliente = $cliente["nombre"] ?? "Cliente";
                            $imagen_cliente = $cliente["logo"] ?? ($cliente["imagen"] ?? "");
                        ?>

                        <div class="client-logo-card">
                            <?php if ($imagen_cliente !== ""): ?>
                                <img src="<?= htmlspecialchars($imagen_cliente) ?>" alt="<?= htmlspecialchars($nombre_cliente) ?>">
                            <?php else: ?>
                                <span><?= htmlspecialchars($nombre_cliente) ?></span>
                            <?php endif; ?>
                        </div>

                    <?php endforeach; ?>

                </div>
            </div>

        <?php endif; ?>
    </div>
</section>

    <section class="reviews section bg-soft">
        <div class="container">
            <h2 class="section-title">Reseñas</h2>

            <div class="reviews-grid">

                <?php foreach ($resenas as $resena): ?>
                    <?php
                        $titulo_resena = $resena["titulo"] ?? "";
                        $comentario_resena = $resena["comentario"] ?? "";
                        $nombre_resena = $resena["nombre"] ?? "";
                        $cargo_resena = $resena["cargo"] ?? "";
                        $foto_resena = $resena["foto"] ?? "";
                        $estrellas_resena = isset($resena["estrellas"]) ? (int)$resena["estrellas"] : 5;

                        if ($estrellas_resena < 1) {
                            $estrellas_resena = 1;
                        }

                        if ($estrellas_resena > 5) {
                            $estrellas_resena = 5;
                        }

                        $ruta_foto_servidor = $foto_resena !== "" ? __DIR__ . "/" . $foto_resena : "";
                        $existe_foto = $foto_resena !== "" && file_exists($ruta_foto_servidor);
                        $inicial = $nombre_resena !== "" ? strtoupper(substr($nombre_resena, 0, 1)) : "Z";
                    ?>

                    <article class="review-card">
                        <div class="stars"><?= str_repeat("★", $estrellas_resena) ?></div>

                        <h3><?= htmlspecialchars($titulo_resena) ?></h3>

                        <p>
                            <?= htmlspecialchars($comentario_resena) ?>
                        </p>

                        <div class="review-author">
                            <div class="review-author-photo">
                                <?php if ($existe_foto): ?>
                                    <img src="<?= htmlspecialchars($foto_resena) ?>" alt="<?= htmlspecialchars($nombre_resena) ?>">
                                <?php else: ?>
                                    <span><?= htmlspecialchars($inicial) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="review-author-info">
                                <strong><?= htmlspecialchars($nombre_resena) ?></strong>
                                <span><?= htmlspecialchars($cargo_resena) ?></span>
                            </div>
                        </div>
                    </article>

                <?php endforeach; ?>

            </div>
        </div>
    </section>

    <section class="budget-highlight">
        <div class="container">
            <div class="budget-card">
                <div class="budget-card-content">
                    <span class="eyebrow">Proyecto a medida</span>
                    <h2>¿Quieres una propuesta personalizada?</h2>
                    <p>
                        Cuéntanos qué espacio, evento o proyecto quieres transformar y prepararemos una solución adaptada a tus objetivos.
                    </p>
                </div>

                <div class="budget-card-actions">
                    <a href="contacto.php" class="btn btn-primary">Pedir presupuesto</a>
                    <a href="<?= $whatsapp_url ?>" target="_blank" class="btn btn-secondary">Hablar por WhatsApp</a>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-preview section">
        <div class="container contact-grid">
            <div>
                <h2>¿Tienes un proyecto en mente?</h2>

                <p>
                    Cuéntanos qué necesitas y prepararemos una propuesta adaptada a tu empresa, evento o espacio.
                </p>

                <div class="contact-data">
                    <p>☎ <?= $telefono ?></p>
                    <p>✉ <?= $email ?></p>
                </div>
            </div>

            <form class="contact-form" action="procesar_contacto.php" method="post">
                <label>Nombre</label>
                <input type="text" name="nombre" placeholder="Tu nombre" required>

                <label>Email</label>
                <input type="email" name="email" placeholder="Tu email" required>

                <input type="hidden" name="servicio" value="Consulta desde página principal">

                <label>Mensaje</label>
                <textarea name="mensaje" placeholder="Cuéntanos qué necesitas" required></textarea>

                <input 
                    type="text" 
                    name="empresa_web" 
                    class="campo-trampa" 
                    tabindex="-1" 
                    autocomplete="off"
                >

                <input 
                    type="hidden" 
                    name="form_inicio" 
                    value="<?= time() ?>"
                >

                <div 
                    class="cf-turnstile" 
                    data-sitekey="<?= htmlspecialchars($turnstile_site_key) ?>"
                ></div>

                <label class="checkbox-line">
                    <input type="checkbox" name="privacidad" required>
                    <span>He leído y acepto la política de privacidad.</span>
                </label>

                <button type="submit" class="btn btn-primary">Contactar</button>
            </form>
        </div>
    </section>

</main>

<script>
window.addEventListener("load", function () {
    const visorEmpresa = document.getElementById("empresa360");

    if (!visorEmpresa || typeof pannellum === "undefined") {
        return;
    }

    visorEmpresa.innerHTML = "";

    const empresaViewer = pannellum.viewer("empresa360", {
        type: "equirectangular",
        panorama: "assets/img/empresa.jpg?v=10",
        autoLoad: true,
        autoRotate: -2,
        compass: false,
        showZoomCtrl: false,
        showFullscreenCtrl: true,
        mouseZoom: true,
        draggable: true,
        hfov: 100
    });

    function reajustarVisor360() {
        if (empresaViewer && typeof empresaViewer.resize === "function") {
            empresaViewer.resize();
        }
    }

    setTimeout(reajustarVisor360, 200);
    setTimeout(reajustarVisor360, 700);
    setTimeout(reajustarVisor360, 1300);

    window.addEventListener("resize", function () {
        setTimeout(reajustarVisor360, 250);
    });

    window.addEventListener("orientationchange", function () {
        setTimeout(reajustarVisor360, 500);
    });
});
</script>

<?php include "includes/footer.php"; ?>