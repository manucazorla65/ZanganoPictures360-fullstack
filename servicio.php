<?php 
include "includes/header.php"; 
require_once "includes/conexion.php";
require_once "includes/formato.php";

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

if ($id <= 0) {
    header("Location: servicios.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM servicios WHERE id = :id AND activo = 1 LIMIT 1");
$stmt->execute([":id" => $id]);
$servicio = $stmt->fetch();

if (!$servicio) {
    header("Location: servicios.php");
    exit;
}

function normalizar_servicio_clave($texto)
{
    $texto = strtolower(trim($texto));

    $texto = strtr($texto, [
        "á" => "a",
        "é" => "e",
        "í" => "i",
        "ó" => "o",
        "ú" => "u",
        "Á" => "a",
        "É" => "e",
        "Í" => "i",
        "Ó" => "o",
        "Ú" => "u",
        "ñ" => "n",
        "Ñ" => "n"
    ]);

    $texto = preg_replace("/[^a-z0-9]+/", "", $texto);

    return $texto;
}

function youtube_embed_url($url)
{
    $url = trim($url);

    if ($url === "") {
        return "";
    }

    if (strpos($url, "embed/") !== false) {
        return $url;
    }

    $video_id = "";

    if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
        $video_id = $matches[1];
    }

    if (preg_match('/v=([a-zA-Z0-9_-]+)/', $url, $matches)) {
        $video_id = $matches[1];
    }

    if (preg_match('/shorts\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
        $video_id = $matches[1];
    }

    if ($video_id === "") {
        return $url;
    }

    return "https://www.youtube.com/embed/" . $video_id;
}

$clave_servicio = normalizar_servicio_clave($servicio["titulo"] ?? "");

$descripcion_larga = $servicio["descripcion_larga"] ?? "";
$parrafos_descripcion = obtener_parrafos_formateados($descripcion_larga);

$tours_por_servicio = [
    "turisverso" => [
        [
            "titulo" => "Ibros",
            "descripcion" => "Experiencia virtual inmersiva para descubrir enclaves turísticos desde cualquier dispositivo.",
            "url" => "https://jaenparaisointerior.online/ibros/"
        ],
        [
            "titulo" => "Alfar Paco y Pablo Tito",
            "descripcion" => "Recorrido virtual para mostrar espacios culturales, artesanales y patrimoniales de forma interactiva.",
            "url" => "https://ubedaybaezaturismo.online/alfarpacoypablotito/"
        ],
        [
            "titulo" => "La Guardia de Jaén",
            "descripcion" => "Tour virtual turístico pensado para acercar el destino al visitante antes de su llegada.",
            "url" => "https://jaenparaisointerior.online/laguardiadejaen/"
        ]
    ],

    "alojaverso" => [
        [
            "titulo" => "Hotel Palacio de Úbeda",
            "descripcion" => "Tour virtual para mostrar habitaciones, espacios comunes y experiencia del alojamiento.",
            "url" => "https://visualizador3d.com/hotelpalacioubedagl/"
        ],
        [
            "titulo" => "Hotel Poeta Jorge Manrique",
            "descripcion" => "Recorrido inmersivo para presentar un alojamiento de forma profesional y visual.",
            "url" => "https://jaenparaisointerior.online/hotelpoetajorgemanrique/"
        ],
        [
            "titulo" => "Apartamentos Turísticos La Estación",
            "descripcion" => "Experiencia virtual diseñada para que el cliente explore el alojamiento antes de reservar.",
            "url" => "https://ubedaybaezaturismo.online/apartamentosturisticoslaestacion/"
        ]
    ],

    "socialverso" => [
        [
            "titulo" => "Aquavera",
            "descripcion" => "Ejemplo de experiencia virtual aplicada a espacios sociales, eventos y entornos de ocio.",
            "url" => "https://visualizador3d.com/aquavera/"
        ],
        [
            "titulo" => "Llano de la Alameda",
            "descripcion" => "Recorrido inmersivo para espacios abiertos, celebraciones y experiencias sociales.",
            "url" => "https://visualizador3d.com/llanodelaalameda/"
        ],
        [
            "titulo" => "Hotel Restaurante Baños",
            "descripcion" => "Tour virtual orientado a restauración, eventos, celebraciones y espacios gastronómicos.",
            "url" => "https://visualizador3d.com/hotelrestaurantebanos/"
        ]
    ]
];

$imagenes_por_servicio = [
    "domoverso" => [
        [
            "titulo" => "Experiencia inmersiva 360º",
            "descripcion" => "Proyección envolvente para transportar al espectador a un espacio visual único.",
            "imagen" => "assets/img/servicios/domoverso/domoverso-1.jpg"
        ],
        [
            "titulo" => "Domo para eventos y presentaciones",
            "descripcion" => "Una solución visual impactante para ferias, congresos, turismo, cultura y educación.",
            "imagen" => "assets/img/servicios/domoverso/domoverso-2.jpg"
        ],
        [
            "titulo" => "Contenido audiovisual envolvente",
            "descripcion" => "Diseñado para captar la atención del público mediante una experiencia sensorial completa.",
            "imagen" => "assets/img/servicios/domoverso/domoverso-3.jpg"
        ]
    ],

    "oleoverso" => [
        [
            "titulo" => "Experiencias oleoturísticas inmersivas",
            "descripcion" => "Muestra al visitante el mundo del aceite de oliva desde una perspectiva visual, cercana e interactiva.",
            "imagen" => "assets/img/servicios/oleoverso/oleoverso-1.jpg"
        ],
        [
            "titulo" => "Almazaras, olivares y cultura del aceite",
            "descripcion" => "Una forma atractiva de presentar procesos, espacios y experiencias relacionadas con el oleoturismo.",
            "imagen" => "assets/img/servicios/oleoverso/oleoverso-2.jpg"
        ],
        [
            "titulo" => "Contenido visual para diferenciar tu proyecto",
            "descripcion" => "Fotografía, vídeo y experiencias 360º pensadas para promocionar marcas, territorios y productos oleícolas.",
            "imagen" => "assets/img/servicios/oleoverso/oleoverso-3.jpg"
        ]
    ]
];

$tiene_tours_especiales = isset($tours_por_servicio[$clave_servicio]);
$tiene_galeria_imagenes = isset($imagenes_por_servicio[$clave_servicio]);

$media_servicio = [];

if (!$tiene_tours_especiales && !$tiene_galeria_imagenes) {
    try {
        $stmt_tabla = $pdo->prepare("SHOW TABLES LIKE 'servicio_media'");
        $stmt_tabla->execute();
        $existe_tabla_media = $stmt_tabla->fetchColumn();

        if ($existe_tabla_media) {
            $stmt_media = $pdo->prepare("SELECT * FROM servicio_media WHERE servicio_id = :servicio_id AND activo = 1 ORDER BY orden ASC, id ASC");
            $stmt_media->execute([":servicio_id" => $id]);
            $media_servicio = $stmt_media->fetchAll();
        }
    } catch (PDOException $e) {
        $media_servicio = [];
    }
}
?>

<main>

    <?php
    $heroes_servicios = [
        "turisverso" => "assets/img/heroes/turisverso_fondo.png",
        "alojaverso" => "assets/img/heroes/alojaverso_fondo.png",
        "oleoverso" => "assets/img/heroes/oleoverso_fondo.png",
        "domoverso" => "assets/img/heroes/domoverso_fondo.png",
        "socialverso" => "assets/img/heroes/socialverso_fondo.png"
    ];

    $hero_servicio_actual = $heroes_servicios[$clave_servicio] ?? "";

    $hero_style = "";

    if ($hero_servicio_actual !== "") {
        $hero_url = htmlspecialchars($hero_servicio_actual . "?v=2", ENT_QUOTES, "UTF-8");

        /*
            Lo ponemos también inline para que no dependa de que otra regla
            antigua del CSS pise el background.
        */
        $hero_style = "background-image: "
            . "linear-gradient(90deg, rgba(7, 27, 45, 0.92) 0%, rgba(7, 27, 45, 0.62) 42%, rgba(7, 27, 45, 0.30) 100%), "
            . "url('" . $hero_url . "'), "
            . "linear-gradient(135deg, #071b2d, #102f4f);";
    }
    ?>

    <section 
        class="service-hero-clean <?= $hero_servicio_actual !== "" ? "service-hero-with-bg" : "" ?>"
        <?= $hero_style !== "" ? 'style="' . $hero_style . '"' : "" ?>
    >
        <div class="service-hero-bg-overlay"></div>

        <div class="service-watermark service-watermark-360">360º</div>
        <div class="service-watermark service-watermark-brand">Zángano Pictures</div>

        <div class="container">
            <span class="eyebrow">Servicio especializado</span>

            <h1><?= htmlspecialchars($servicio["titulo"]) ?></h1>

            <p>
                <?= htmlspecialchars($servicio["descripcion_corta"]) ?>
            </p>

            <div class="hero-buttons">
                <a href="contacto.php" class="btn btn-primary">Solicitar presupuesto</a>
                <a href="servicios.php" class="btn btn-secondary">Ver todos los servicios</a>
            </div>
        </div>
    </section>

    <section class="section service-detail-content">
        <div class="container detail-grid">

            <div class="detail-text">
                <span class="eyebrow">Qué hacemos</span>
                <h2>Una solución visual pensada para impactar</h2>

                <?php if (empty($parrafos_descripcion)): ?>
                    <p>
                        Próximamente añadiremos más información sobre este servicio.
                    </p>
                <?php else: ?>
                    <?php foreach ($parrafos_descripcion as $parrafo): ?>
                        <p><?= $parrafo ?></p>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class="detail-actions">
                    <a href="contacto.php" class="btn btn-primary">Quiero este servicio</a>
                    <a href="<?= $whatsapp_url ?>" target="_blank" class="btn btn-secondary">Hablar por WhatsApp</a>
                </div>
            </div>

            <div class="floating-panel">
                <h3>Este servicio es ideal para:</h3>

                <ul>
                    <li>Empresas que quieren diferenciarse.</li>
                    <li>Espacios turísticos y culturales.</li>
                    <li>Eventos, ferias y presentaciones.</li>
                    <li>Hoteles, restaurantes y alojamientos.</li>
                    <li>Proyectos que necesitan una imagen moderna.</li>
                </ul>
            </div>

        </div>
    </section>

    <?php if ($tiene_tours_especiales): ?>

        <section class="section bg-soft service-tour-examples-section">
            <div class="container">
                <h2 class="section-title">Ejemplos interactivos</h2>
                <p class="section-subtitle">
                    Explora algunos ejemplos reales de experiencias virtuales relacionadas con <?= htmlspecialchars($servicio["titulo"]) ?>.
                </p>

                <div class="service-tour-list">
                    <?php foreach ($tours_por_servicio[$clave_servicio] as $tour): ?>
                        <article class="service-tour-item">
                            <div class="service-tour-frame">
                                <iframe 
                                    src="<?= htmlspecialchars($tour["url"]) ?>"
                                    title="<?= htmlspecialchars($tour["titulo"]) ?>"
                                    loading="lazy"
                                    allowfullscreen>
                                </iframe>
                            </div>

                            <div class="service-tour-info">
                                <span class="eyebrow">Tour virtual</span>
                                <h3><?= htmlspecialchars($tour["titulo"]) ?></h3>
                                <p><?= htmlspecialchars($tour["descripcion"]) ?></p>

                                <div class="detail-actions">
                                    <a href="<?= htmlspecialchars($tour["url"]) ?>" target="_blank" class="btn btn-primary">
                                        Abrir tour completo
                                    </a>

                                    <a href="contacto.php" class="btn btn-secondary">
                                        Quiero algo parecido
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

    <?php elseif ($tiene_galeria_imagenes): ?>

        <section class="section bg-soft service-domoverso-section">
            <div class="container">
                <h2 class="section-title">Ejemplos visuales de <?= htmlspecialchars($servicio["titulo"]) ?></h2>
                <p class="section-subtitle">
                    Una muestra visual de cómo puede presentarse este servicio mediante contenido profesional, atractivo e inmersivo.
                </p>

                <div class="domoverso-gallery-grid">
                    <?php foreach ($imagenes_por_servicio[$clave_servicio] as $imagen_servicio): ?>
                        <article class="domoverso-card">
                            <div class="domoverso-card-image">
                                <?php if (file_exists(__DIR__ . "/" . $imagen_servicio["imagen"])): ?>
                                    <img 
                                        src="<?= htmlspecialchars($imagen_servicio["imagen"]) ?>" 
                                        alt="<?= htmlspecialchars($imagen_servicio["titulo"]) ?>"
                                    >
                                <?php else: ?>
                                    <div class="domoverso-placeholder">
                                        <span><?= htmlspecialchars($servicio["titulo"]) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="domoverso-card-content">
                                <h3><?= htmlspecialchars($imagen_servicio["titulo"]) ?></h3>
                                <p><?= htmlspecialchars($imagen_servicio["descripcion"]) ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

    <?php else: ?>

        <section class="section bg-soft service-media-section">
            <div class="container">
                <h2 class="section-title">Imágenes y vídeos del servicio</h2>
                <p class="section-subtitle">
                    Una muestra visual de cómo puede presentarse este servicio mediante contenido inmersivo, audiovisual y profesional.
                </p>

                <?php if (empty($media_servicio)): ?>

                    <div class="gallery-grid">
                        <div class="gallery-item">
                            <img src="<?= htmlspecialchars($servicio["imagen"]) ?>" alt="<?= htmlspecialchars($servicio["titulo"]) ?>">
                        </div>

                        <div class="gallery-item">
                            <img src="<?= htmlspecialchars($servicio["imagen"]) ?>" alt="<?= htmlspecialchars($servicio["titulo"]) ?>">
                        </div>

                        <div class="gallery-item">
                            <img src="<?= htmlspecialchars($servicio["imagen"]) ?>" alt="<?= htmlspecialchars($servicio["titulo"]) ?>">
                        </div>
                    </div>

                <?php else: ?>

                    <div class="media-gallery-grid">
                        <?php foreach ($media_servicio as $media): ?>
                            <?php
                                $tipo = $media["tipo"] ?? "imagen";
                                $titulo_media = $media["titulo"] ?? "";
                                $descripcion_media = $media["descripcion"] ?? "";
                                $url_media = $media["url"] ?? "";
                                $parrafos_media = obtener_parrafos_formateados($descripcion_media);
                            ?>

                            <article class="media-card">
                                <div class="media-frame">

                                    <?php if ($tipo === "imagen"): ?>

                                        <img src="<?= htmlspecialchars($url_media) ?>" alt="<?= htmlspecialchars($titulo_media ?: $servicio["titulo"]) ?>">

                                    <?php elseif ($tipo === "video"): ?>

                                        <video controls preload="metadata">
                                            <source src="<?= htmlspecialchars($url_media) ?>" type="video/mp4">
                                            Tu navegador no puede reproducir este vídeo.
                                        </video>

                                    <?php elseif ($tipo === "youtube"): ?>

                                        <iframe 
                                            src="<?= htmlspecialchars(youtube_embed_url($url_media)) ?>" 
                                            title="<?= htmlspecialchars($titulo_media ?: $servicio["titulo"]) ?>"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                            allowfullscreen>
                                        </iframe>

                                    <?php endif; ?>

                                </div>

                                <?php if ($titulo_media !== "" || $descripcion_media !== ""): ?>
                                    <div class="media-card-content">
                                        <?php if ($titulo_media !== ""): ?>
                                            <h3><?= htmlspecialchars($titulo_media) ?></h3>
                                        <?php endif; ?>

                                        <?php foreach ($parrafos_media as $parrafo_media): ?>
                                            <p><?= $parrafo_media ?></p>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </article>

                        <?php endforeach; ?>
                    </div>

                <?php endif; ?>
            </div>
        </section>

    <?php endif; ?>

    <section class="section cta-section">
        <div class="container">
            <div class="cta-box">
                <h2>¿Quieres aplicar <?= htmlspecialchars(strtolower($servicio["titulo"])) ?> a tu proyecto?</h2>
                <p>
                    Escríbenos y prepararemos una propuesta adaptada a tus necesidades.
                </p>
                <a href="contacto.php" class="btn btn-primary">Contactar ahora</a>
            </div>
        </div>
    </section>

</main>

<?php include "includes/footer.php"; ?>