<?php
require_once __DIR__ . '/../models/AuthModel.php';

class UsuarioController {
    /**
     * @var AuthModel Instancia del modelo de autenticación para operaciones en base de datos
     */
    private AuthModel $authModel;

    /**
     * Constructor de la clase
     * Inicializa la sesión si no está iniciada y crea una instancia de AuthModel
     */
    public function __construct() {
        // Verificar si la sesión ya está activa antes de iniciarla
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->authModel = new AuthModel();
    }

    /**
     * Maneja el proceso de inicio de sesión de los usuarios (Administradores, Manicuristas o Clientes)
     */
    public function login() {
        // Si la petición es GET, mostrar la vista del formulario de login
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once __DIR__ . '/../views/auth/login.php';
            exit;
        }

        // Si la petición es POST, procesar los datos del formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitizar el correo electrónico recibido
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            
            // Intentar iniciar sesión verificando en cascada (Admin -> Manicurista -> Cliente)
            $user = $this->authModel->loginCascada($email, $password);
            
            if ($user) {
                // Credenciales correctas: Iniciar sesión y guardar datos en $_SESSION
                $_SESSION['user'] = [
                    // Se obtiene el ID correspondiente dependiendo del rol que haya hecho match
                    'id' => $user['id_cliente'] ?? $user['id_manicurista'] ?? $user['id_administrador'],
                    'nombre' => htmlspecialchars($user['nombre']), // Evitar XSS
                    'email' => htmlspecialchars($user['email']),
                    'rol' => $user['rol']
                ];

                // Redirigir al dashboard correspondiente según el rol del usuario
                if ($user['rol'] === 'administrador') {
                    header("Location: index.php?url=admin/dashboard");
                } elseif ($user['rol'] === 'manicurista') {
                    header("Location: index.php?url=manicurista/dashboard");
                } else {
                    header("Location: index.php?url=cliente/dashboard");
                }
                exit;
            } else {
                // Credenciales inválidas: Guardar mensaje de error en sesión y redirigir al login
                $_SESSION['error'] = "Credenciales inválidas";
                header("Location: index.php?url=usuario/login");
                exit;
            }
        }
    }

    /**
     * Maneja el proceso de cierre de sesión
     */
    public function logout() {
        // Vaciar todas las variables de sesión
        $_SESSION = [];
        
        // Destruir la cookie de sesión si existe
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Finalmente destruir la sesión en el servidor
        session_destroy();

        // Redirigir de manera segura a la página principal usando la constante BASE_URL
        header('Location: ' . BASE_URL . 'index.php');
        exit;
    }
}
