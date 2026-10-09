# Arquitectura General - New Creacion Nails

Este documento describe la arquitectura base del sistema, que sigue el patrón de diseño **MVC (Modelo-Vista-Controlador)** construido en PHP nativo.

## Estructura de Directorios

- `config/`: Configuración global y conexión PDO a la base de datos (`database.php`).
- `controllers/`: Lógica de negocio e intermediación. Reciben peticiones del usuario, interactúan con los modelos y cargan las vistas.
- `models/`: Lógica de base de datos. Contienen clases que encapsulan las consultas SQL usando PDO preparadas.
- `views/`: Archivos PHP que renderizan el HTML. Se organizan por actor (`admin/`, `cliente/`, `manicurista/`, `auth/`).
- `public/`: Archivos estáticos accesibles directamente por el navegador (CSS, JS, imágenes, y subidas de comprobantes).
- `index.php`: Front Controller. Único punto de entrada de la aplicación que se encarga del enrutamiento basado en un parámetro `url` por GET.

## Tecnologías Utilizadas
- **Backend**: PHP 8+ (PDO para base de datos).
- **Frontend**: HTML5, CSS3, JavaScript básico (Vanilla).
- **Base de Datos**: MySQL/MariaDB (Relacional).
- **Servidor Local**: Laragon (Apache/Nginx).

## Seguridad Implementada
- Autenticación con manejo de sesiones (`$_SESSION`).
- Encriptación de contraseñas usando `PASSWORD_BCRYPT` (`password_hash` y `password_verify`).
- Sanitización de entradas (uso de `filter_input`).
- Prevención de SQL Injection a través de Consultas Preparadas (PDO `prepare` y `execute`).
