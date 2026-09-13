<?php include "includes/header.php"; ?>

<main>

    <section class="page-hero legal-hero">
        <div class="container">
            <span class="eyebrow">Protección de datos</span>
            <h1>Política de privacidad</h1>
            <p>
                Información sobre cómo se gestionan los datos enviados a través de los formularios de contacto.
            </p>
        </div>
    </section>

    <section class="section legal-section">
        <div class="container legal-content">

            <article class="legal-card">
                <h2>1. Responsable del tratamiento</h2>

                <p>
                    El responsable del tratamiento de los datos personales recogidos a través de esta web es Zángano Pictures 360.
                </p>

                <ul>
                    <li><strong>Email de contacto:</strong> <?= $email ?></li>
                    <li><strong>Teléfono:</strong> <?= $telefono ?></li>
                </ul>
            </article>

            <article class="legal-card">
                <h2>2. Datos que se recogen</h2>

                <p>
                    A través del formulario de contacto se pueden recoger los siguientes datos:
                </p>

                <ul>
                    <li>Nombre.</li>
                    <li>Correo electrónico.</li>
                    <li>Teléfono.</li>
                    <li>Servicio de interés.</li>
                    <li>Mensaje enviado por el usuario.</li>
                </ul>
            </article>

            <article class="legal-card">
                <h2>3. Finalidad del tratamiento</h2>

                <p>
                    Los datos se utilizan únicamente para responder consultas, preparar propuestas comerciales, gestionar solicitudes de información y mantener la comunicación relacionada con los servicios solicitados.
                </p>
            </article>

            <article class="legal-card">
                <h2>4. Conservación de los datos</h2>

                <p>
                    Los datos se conservarán durante el tiempo necesario para atender la solicitud recibida y, en su caso, mientras exista una relación comercial o administrativa con el usuario.
                </p>
            </article>

            <article class="legal-card">
                <h2>5. Comunicación de datos a terceros</h2>

                <p>
                    No se cederán datos personales a terceros salvo obligación legal o cuando sea necesario para prestar correctamente un servicio solicitado por el usuario.
                </p>
            </article>

            <article class="legal-card">
                <h2>6. Derechos del usuario</h2>

                <p>
                    El usuario puede solicitar el acceso, rectificación, supresión, oposición, limitación del tratamiento o portabilidad de sus datos escribiendo a:
                </p>

                <p>
                    <strong><?= $email ?></strong>
                </p>
            </article>

            <article class="legal-card">
                <h2>7. Seguridad</h2>

                <p>
                    Zángano Pictures 360 aplica medidas razonables para proteger la información recibida a través de esta web y evitar accesos no autorizados.
                </p>
            </article>

        </div>
    </section>

</main>

<?php include "includes/footer.php"; ?>