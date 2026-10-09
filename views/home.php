<?php
// Conexión a la base de datos (se verifica al cargar la página)
require_once __DIR__ . '/../config/database.php';
// $conn ya está disponible por el include de database.php y validó la conexión exitosamente.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creación Nails Studio & Spa | Agendamiento Exclusivo</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= defined('BASE_URL') ? BASE_URL : '/' ?>public/css/style.css">
</head>
<body>
    <!-- Top Announcement Bar -->
    <div class="announcement-bar">
        ✨ Experiencia exclusiva bajo cita previa. Reserva tu espacio y déjate consentir. ✨
    </div>

    <!-- Header / Navbar -->
    <header class="header">
        <div class="container header-content">
            <a href="<?= defined('BASE_URL') ? BASE_URL : '/' ?>index.php" class="logo">
                <i data-lucide="sparkles"></i>
                Creación Nails
            </a>
            
            <nav class="nav-links">
                <a href="#servicios" class="nav-link">Servicios</a>
                <a href="#como-funciona" class="nav-link">Cómo Funciona</a>
                <a href="#contacto" class="nav-link">Contacto</a>
            </nav>
            
            <div class="header-actions">
                <a href="<?= defined('BASE_URL') ? BASE_URL : '/' ?>index.php?url=usuario/login" class="btn btn-outline">Iniciar Sesión</a>
                <a href="<?= defined('BASE_URL') ? BASE_URL : '/' ?>index.php?url=register" class="btn btn-primary">Registrarse</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <span class="hero-badge">Estudio Especializado de Alta Gama</span>
            <h1 class="hero-title">El arte y cuidado que tus manos merecen</h1>
            <p class="hero-subtitle">Descubre una experiencia de relajación y belleza incomparable. Técnicas vanguardistas, atención personalizada y resultados deslumbrantes.</p>
            
            <a href="<?= defined('BASE_URL') ? BASE_URL : '/' ?>index.php?url=register" class="btn btn-primary" style="font-size: 1.125rem; padding: 0.875rem 2rem;">
                Agendar Cita Ahora
                <i data-lucide="calendar-heart"></i>
            </a>

            <div class="hero-image-wrapper">
                <img src="<?= defined('BASE_URL') ? BASE_URL : '/' ?>public/uploads/hero-image.jpg" alt="Nail Art Spa" class="hero-image">
            </div>
        </div>
    </section>

    <!-- Procedimientos Destacados -->
    <section id="servicios" class="services">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Procedimientos Destacados</h2>
                <p class="section-subtitle">Técnicas realizadas por profesionales expertas</p>
            </div>

            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i data-lucide="scissors"></i>
                    </div>
                    <h3 class="service-title">Acrílicas Esculpidas</h3>
                    <p class="service-desc">Alargamiento y diseño con acrílico de alta resistencia. Acabado natural o dramático según tu preferencia.</p>
                    <div class="service-meta">
                        <span class="service-price">$ 85.000 COP</span>
                        <span class="service-duration">
                            <i data-lucide="clock" style="width: 16px; height: 16px;"></i>
                            120 min
                        </span>
                    </div>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i data-lucide="sparkle"></i>
                    </div>
                    <h3 class="service-title">Esmaltado Semipermanente</h3>
                    <p class="service-desc">Color vibrante y duradero hasta por 21 días. Incluye preparación completa de la uña natural.</p>
                    <div class="service-meta">
                        <span class="service-price">$ 45.000 COP</span>
                        <span class="service-duration">
                            <i data-lucide="clock" style="width: 16px; height: 16px;"></i>
                            60 min
                        </span>
                    </div>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i data-lucide="droplets"></i>
                    </div>
                    <h3 class="service-title">Spa Tradicional</h3>
                    <p class="service-desc">Limpieza profunda, exfoliación, masaje relajante e hidratación intensiva. Finalizado con esmalte regular.</p>
                    <div class="service-meta">
                        <span class="service-price">$ 30.000 COP</span>
                        <span class="service-duration">
                            <i data-lucide="clock" style="width: 16px; height: 16px;"></i>
                            45 min
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Metodología de reserva (Cómo funciona) -->
    <section id="como-funciona" class="how-it-works">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Tu cita en 3 pasos</h2>
                <p class="section-subtitle">Sistema seguro de reserva con abono previo</p>
            </div>

            <div class="steps-grid">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <h3 class="step-title">Selección de Servicio</h3>
                    <p class="step-desc">Regístrate y elige el servicio, la profesional y el horario de tu preferencia según disponibilidad.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">2</div>
                    <h3 class="step-title">Soporte de Abono</h3>
                    <p class="step-desc">Sube el comprobante de transferencia (Nequi/Daviplata) para asegurar tu espacio de manera inmediata.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">3</div>
                    <h3 class="step-title">Confirmación</h3>
                    <p class="step-desc">Nuestra administración validará el abono y tu cita quedará oficialmente confirmada en el sistema.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-col">
                    <a href="<?= defined('BASE_URL') ? BASE_URL : '/' ?>index.php" class="logo" style="color: var(--color-white); margin-bottom: 1rem; font-size: 1.25rem;">
                        <i data-lucide="sparkles"></i>
                        Creación Nails
                    </a>
                    <p style="color: #A8A29E; font-size: 0.875rem; margin-top: 1rem; max-width: 250px;">
                        Experiencias de belleza, arte y cuidado en un solo lugar.
                    </p>
                </div>
                
                <div class="footer-col">
                    <h4>Enlaces Rápidos</h4>
                    <ul class="footer-links">
                        <li><a href="#servicios">Nuestros Servicios</a></li>
                        <li><a href="#como-funciona">Cómo Agendar</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                &copy; <?php echo date('Y'); ?> Creación Nails Studio & Spa. Todos los derechos reservados.
            </div>
        </div>
    </footer>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
      lucide.createIcons();
    </script>
</body>
</html>
