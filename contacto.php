<?php include "includes/header.php"; ?>

<main>

   <section 
        class="page-hero page-hero-with-bg"
        style="--hero-bg: url('assets/img/heroes/contacto_fondo.png?v=1');"
    >
        <div class="service-hero-bg-overlay"></div>
        <div class="service-watermark service-watermark-360">360º</div>

        <div class="container">
            <span class="eyebrow">Hablemos de tu proyecto</span>
            <h1>Contacto</h1>
            <p>
                Cuéntanos qué necesitas y te responderemos con una propuesta adaptada a tu empresa, espacio o evento.
            </p>
        </div>
    </section>

    <section class="section contact-page">
        <div class="container contact-grid">

            <div class="contact-info-card">
                <span class="eyebrow">Zángano Pictures 360</span>

                <h2>Transforma tu espacio en una experiencia inmersiva</h2>

                <p>
                    Si quieres mejorar la forma en la que tus clientes ven tu empresa, tu local, tu alojamiento,
                    tu evento o tu proyecto, estamos preparados para ayudarte.
                </p>

                <div class="contact-list">
                    <p><strong>Teléfono:</strong> <?= $telefono ?></p>
                    <p><strong>Email:</strong> <?= $email ?></p>
                    <p><strong>WhatsApp:</strong> <?= $telefono ?></p>
                </div>

                <a href="<?= $whatsapp_url ?>" target="_blank" class="btn btn-primary">
                    Contactar por WhatsApp
                </a>
            </div>

            <form class="contact-form" action="procesar_contacto.php" method="post">

                <?php if (isset($_GET["ok"])): ?>
                    <div class="alert alert-success">
                        Tu mensaje se ha enviado correctamente. Te responderemos lo antes posible.
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET["error"])): ?>
                    <div class="alert alert-error">
                        <?= htmlspecialchars($_GET["error"]) ?>
                    </div>
                <?php endif; ?>

                <label>Nombre</label>
                <input type="text" name="nombre" placeholder="Tu nombre" required>

                <label>Email</label>
                <input type="email" name="email" placeholder="Tu correo electrónico" required>

                <label>Teléfono</label>
                <input type="tel" name="telefono" placeholder="Tu teléfono">

                <label>Servicio que te interesa</label>
               <select name="servicio">
                <option value="">Selecciona una opción</option>
                <option value="Turisverso">Turisverso</option>
                <option value="Alojaverso">Alojaverso</option>
                <option value="Oleoverso">Oleoverso</option>
                <option value="Domoverso">Domoverso</option>
                <option value="Socialverso">Socialverso</option>
            </select>

                <label>Mensaje</label>
                <textarea name="mensaje" placeholder="Cuéntanos qué tienes en mente" required></textarea>

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

                <button type="submit" class="btn btn-primary">Enviar mensaje</button>
            </form>

        </div>
    </section>

</main>

<?php include "includes/footer.php"; ?>