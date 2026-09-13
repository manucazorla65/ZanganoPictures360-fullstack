document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menuToggle");
    const navMenu = document.getElementById("navMenu");
    const topHeader = document.getElementById("topHeader");
    const dropdownButtons = document.querySelectorAll(".nav-dropdown-button");
    const dropdowns = document.querySelectorAll(".nav-dropdown");

    function cerrarDropdowns(excepto = null) {
        dropdowns.forEach(function (dropdown) {
            if (dropdown !== excepto) {
                dropdown.classList.remove("open");
            }
        });
    }

    /* MENÚ PRINCIPAL EN MÓVIL */
    if (menuToggle && navMenu) {
        menuToggle.addEventListener("click", function (event) {
            event.preventDefault();
            event.stopPropagation();

            navMenu.classList.toggle("active");

            if (!navMenu.classList.contains("active")) {
                cerrarDropdowns();
            }
        });
    }

    /* HEADER AL HACER SCROLL */
    function actualizarHeader() {
        if (!topHeader) {
            return;
        }

        const tieneHeroVisual = document.querySelector(
            ".hero, .hero-carousel, .hero-slider-clean, .page-hero, .service-detail-hero, .service-hero-clean"
        );

        if (!tieneHeroVisual || window.scrollY > 40) {
            topHeader.classList.add("top-header-solid");
        } else {
            topHeader.classList.remove("top-header-solid");
        }
    }

    actualizarHeader();
    window.addEventListener("scroll", actualizarHeader);

    /* SUBMENÚ SERVICIOS */
    dropdownButtons.forEach(function (button) {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            event.stopPropagation();

            const dropdown = button.closest(".nav-dropdown");

            if (!dropdown) {
                return;
            }

            const estabaAbierto = dropdown.classList.contains("open");

            cerrarDropdowns(dropdown);

            if (estabaAbierto) {
                dropdown.classList.remove("open");
            } else {
                dropdown.classList.add("open");
            }
        });
    });

    /* EVITAR QUE SE CIERRE AL PULSAR DENTRO */
    dropdowns.forEach(function (dropdown) {
        dropdown.addEventListener("click", function (event) {
            event.stopPropagation();
        });

        dropdown.addEventListener("mouseenter", function () {
            if (window.innerWidth > 800) {
                cerrarDropdowns(dropdown);
                dropdown.classList.add("open");
            }
        });

        dropdown.addEventListener("mouseleave", function () {
            if (window.innerWidth > 800) {
                setTimeout(function () {
                    dropdown.classList.remove("open");
                }, 180);
            }
        });
    });

    /* SI PULSAS UN ENLACE DEL SUBMENÚ, DEJAMOS QUE NAVEGUE */
    document.querySelectorAll(".dropdown-menu a").forEach(function (link) {
        link.addEventListener("click", function () {
            cerrarDropdowns();

            if (window.innerWidth <= 800 && navMenu) {
                navMenu.classList.remove("active");
            }
        });
    });

    /* CERRAR AL PULSAR FUERA */
    document.addEventListener("click", function (event) {
        if (!event.target.closest(".compact-header")) {
            cerrarDropdowns();

            if (window.innerWidth <= 800 && navMenu) {
                navMenu.classList.remove("active");
            }
        }
    });

    /* CERRAR CON ESC */
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            cerrarDropdowns();

            if (navMenu) {
                navMenu.classList.remove("active");
            }
        }
    });
});