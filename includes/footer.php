<?php
require_once __DIR__ . "/config.php";
?>

<a 
    href="<?= $whatsapp_url ?>" 
    class="whatsapp-float" 
    target="_blank" 
    aria-label="Contactar por WhatsApp"
>
    <svg viewBox="0 0 32 32" aria-hidden="true">
        <path d="M16.04 3C9.44 3 4.08 8.35 4.08 14.95c0 2.1.55 4.16 1.6 5.97L4 29l8.28-1.63a11.9 11.9 0 0 0 5.76 1.47h.01C24.65 28.84 30 23.49 30 16.89 30 10.29 22.64 3 16.04 3Zm0 23.67h-.01a9.7 9.7 0 0 1-4.95-1.35l-.35-.2-4.91.97.98-4.78-.23-.37a9.72 9.72 0 0 1-1.5-5.17c0-5.37 4.37-9.74 9.75-9.74 2.6 0 5.04 1.01 6.88 2.85a9.67 9.67 0 0 1 2.86 6.89c0 5.37-4.37 9.74-9.74 9.74Zm5.34-7.3c-.29-.15-1.72-.85-1.99-.95-.27-.1-.46-.15-.66.15-.19.29-.76.95-.93 1.14-.17.2-.34.22-.63.07-.29-.15-1.23-.45-2.34-1.44-.86-.77-1.45-1.72-1.62-2.01-.17-.29-.02-.45.13-.6.13-.13.29-.34.44-.51.15-.17.2-.29.29-.49.1-.2.05-.37-.02-.52-.07-.15-.66-1.6-.91-2.2-.24-.58-.49-.5-.66-.51h-.56c-.2 0-.52.07-.79.37-.27.29-1.04 1.01-1.04 2.47s1.06 2.87 1.21 3.07c.15.2 2.09 3.19 5.06 4.47.71.31 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.09 1.72-.7 1.96-1.38.24-.68.24-1.26.17-1.38-.07-.12-.27-.2-.56-.35Z"/>
    </svg>
</a>

<footer class="footer-clean">
    <div class="footer-clean-container">

        <div class="footer-clean-brand">
            <a href="index.php" class="footer-clean-logo">
                <img src="assets/img/logo.png?v=3" alt="Zángano Pictures 360">
            </a>

            <div class="footer-clean-contact">
                <a href="tel:<?= str_replace(' ', '', $telefono) ?>">☎ <?= $telefono ?></a>
                <a href="mailto:<?= $email ?>">✉ <?= $email ?></a>
            </div>
        </div>

        <nav class="footer-clean-links">
            <a href="index.php">Inicio</a>
            <a href="servicios.php">Servicios</a>
            <a href="noticias.php">Noticias</a>
            <a href="contacto.php">Contacto</a>
            <a href="aviso-legal.php">Aviso legal</a>
            <a href="privacidad.php">Privacidad</a>
            <a href="cookies.php">Cookies</a>
            <a href="admin/login.php">Admin</a>
        </nav>

        <div class="footer-clean-social">
            <a href="<?= $instagram ?>" target="_blank" class="footer-social-link" aria-label="Instagram">
                <span class="footer-social-icon instagram-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <rect x="3" y="3" width="18" height="18" rx="5"></rect>
                        <circle cx="12" cy="12" r="4"></circle>
                        <circle cx="17.5" cy="6.5" r="1.2"></circle>
                    </svg>
                </span>
                <span>Instagram</span>
            </a>

            <a href="<?= $facebook ?>" target="_blank" class="footer-social-link" aria-label="Facebook">
                <span class="footer-social-icon facebook-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M14 8.5h2.4V5.1A25.4 25.4 0 0 0 12.9 5C9.5 5 7.2 7.1 7.2 10.8V14H4v3.8h3.2V24h4.1v-6.2h3.2L15 14h-3.7v-2.8c0-1.1.3-2.7 2.7-2.7Z"></path>
                    </svg>
                </span>
                <span>Facebook</span>
            </a>

            <a href="<?= $youtube ?>" target="_blank" class="footer-social-link" aria-label="YouTube">
                <span class="footer-social-icon youtube-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path class="youtube-shape" d="M21.6 7.2c-.2-.9-.9-1.6-1.8-1.8C18.2 5 12 5 12 5s-6.2 0-7.8.4c-.9.2-1.6.9-1.8 1.8C2 8.8 2 12 2 12s0 3.2.4 4.8c.2.9.9 1.6 1.8 1.8C5.8 19 12 19 12 19s6.2 0 7.8-.4c.9-.2 1.6-.9 1.8-1.8.4-1.6.4-4.8.4-4.8s0-3.2-.4-4.8Z"></path>
                        <path class="youtube-play" d="M10 9.2v5.6L15.1 12Z"></path>
                    </svg>
                </span>
                <span>YouTube</span>
            </a>
        </div>

    </div>

    <div class="footer-clean-bottom">
        <p>
            © <?= date("Y") ?> Zángano Pictures 360. Todos los derechos reservados.
        </p>

        <p class="footer-developer-credit">
            Software diseñado y desarrollado por <strong>Manuel Cazorla Chica</strong>
        </p>
    </div>

    <div class="footer-foundation-strip">
        <div class="footer-foundation-container">
            <p>
                © ZÁNGANO PICTURES 360 <span>❤️</span> ORGULLOSAMENTE FUNDADA EN TORREQUEBRADILLA.
            </p>

            <img 
                src="assets/img/footer/torrequebradilla.png" 
                alt="Silueta de Torrequebradilla"
            >
        </div>
    </div>
</footer>

<script src="assets/js/main.js?v=3"></script>

</body>
</html>