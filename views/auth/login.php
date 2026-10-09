<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Creación Nails Studio & Spa</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS General -->
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
    
    <!-- Estilos específicos para autenticación (Mismos que register) -->
    <style>
        body {
            background-color: var(--color-rose-light);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .auth-container {
            width: 100%;
            max-width: 450px;
            padding: 2rem;
        }
        .auth-card {
            background: var(--color-white);
            border-radius: var(--radius-lg);
            padding: 2.5rem 2rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(232, 165, 172, 0.3);
        }
        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .auth-logo {
            color: var(--color-gold-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-family: var(--font-heading);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-decoration: none;
        }
        .auth-title {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .auth-subtitle {
            color: var(--color-carbon);
            font-size: 0.875rem;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--color-graphite);
        }
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #D6D3D1;
            border-radius: var(--radius-md);
            font-family: var(--font-body);
            font-size: 0.875rem;
            transition: all 0.2s;
            outline: none;
        }
        .form-control:focus {
            border-color: var(--color-gold-medium);
            box-shadow: 0 0 0 3px rgba(197, 155, 39, 0.1);
        }
        .btn-block {
            width: 100%;
            margin-top: 1rem;
        }
        .alert {
            padding: 1rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }
        .alert-error {
            background-color: #FEF2F2;
            color: #991B1B;
            border: 1px solid #F87171;
        }
        .alert-success {
            background-color: #F0FDF4;
            color: #166534;
            border: 1px solid #4ADE80;
        }
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
            color: var(--color-carbon);
        }
        .auth-footer a {
            color: var(--color-gold-dark);
            font-weight: 600;
            transition: color 0.2s;
        }
        .auth-footer a:hover {
            color: var(--color-gold-medium);
            text-decoration: underline;
        }
        .btn-toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--color-carbon);
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-toggle-password:hover {
            color: var(--color-gold-dark);
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <a href="<?= BASE_URL ?>index.php" class="auth-logo">
                    <i data-lucide="sparkles"></i>
                    Creación Nails
                </a>
                <h1 class="auth-title">Bienvenida de nuevo</h1>
                <p class="auth-subtitle">Inicia sesión para gestionar tus citas</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>index.php?url=usuario/login">
                <div class="form-group">
                    <label class="form-label" for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control" required placeholder="tu@correo.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Contraseña</label>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" class="form-control" required placeholder="Ingresa tu contraseña" style="padding-right: 2.5rem;">
                        <button type="button" class="btn-toggle-password" onclick="togglePassword('password', this)" title="Mostrar contraseña">
                            <i data-lucide="eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    Iniciar Sesión
                    <i data-lucide="log-in"></i>
                </button>
            </form>

            <div class="auth-footer">
                ¿No tienes una cuenta? <a href="<?= BASE_URL ?>index.php?url=register">Regístrate aquí</a><br><br>
                <a href="<?= BASE_URL ?>index.php" style="color: var(--color-carbon); font-weight: normal;">&larr; Volver al inicio</a>
            </div>
        </div>
    </div>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
      lucide.createIcons();

      function togglePassword(inputId, btn) {
          const input = document.getElementById(inputId);
          const icon = btn.querySelector('i');
          if (input.type === 'password') {
              input.type = 'text';
              icon.setAttribute('data-lucide', 'eye-off');
          } else {
              input.type = 'password';
              icon.setAttribute('data-lucide', 'eye');
          }
          lucide.createIcons({
              nameAttr: 'data-lucide'
          });
      }
    </script>
</body>
</html>
