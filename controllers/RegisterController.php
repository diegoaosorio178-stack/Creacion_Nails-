<?php
require_once __DIR__ . '/../models/AuthModel.php';

class RegisterController {
    /**
     * @var AuthModel Instancia del modelo de autenticación para realizar las operaciones en la base de datos
     */
    private AuthModel $authModel;

    /**
     * Constructor de la clase
     * Inicializa la sesión y prepara la conexión al modelo de autenticación
     */
    public function __construct() {
        // Verificar si la sesión no está activa antes de iniciarla
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->authModel = new AuthModel();
    }

    /**
     * Método principal por defecto para manejar el proceso de registro de nuevos clientes.
     * Soporta peticiones GET (para mostrar el formulario) y POST (para procesar el registro).
     */
    public function index() {
        // Si la petición es GET, simplemente mostrar la vista de registro
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once __DIR__ . '/../views/auth/register.php';
            exit;
        }

        // Si la petición es POST, procesar el formulario de registro
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recibir y sanitizar los datos de entrada enviados por el usuario
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
            $password = $_POST['password'] ?? '';

            // Validación 1: Verificar que ningún campo esté vacío
            if (empty($nombre) || empty($email) || empty($password) || empty($telefono)) {
                $_SESSION['error'] = "Todos los campos son obligatorios";
                header("Location: index.php?url=register");
                exit;
            }

            // Validación 2: Verificar si el correo electrónico ya existe en la base de datos
            if ($this->authModel->emailExiste($email)) {
                $_SESSION['error'] = "El correo ya se encuentra registrado";
                header("Location: index.php?url=register");
                exit;
            }

            // Preparar arreglo con los datos validados para la inserción
            $datos = [
                'nombre' => $nombre,
                'email' => $email,
                'telefono' => $telefono,
                'password' => $password
            ];

            // Intentar registrar al nuevo cliente en la base de datos
            if ($this->authModel->registrarCliente($datos)) {
                // Auto login post-registro: Iniciar sesión automáticamente usando los datos recién creados
                $user = $this->authModel->loginCascada($email, $password);
                
                if ($user) {
                    // Guardar los datos de sesión correspondientes al cliente registrado
                    $_SESSION['user'] = [
                        'id' => $user['id_cliente'],
                        'nombre' => htmlspecialchars($user['nombre']), // htmlspecialchars previene inyección XSS
                        'email' => htmlspecialchars($user['email']),
                        'rol' => 'cliente'
                    ];
                    
                    // Redirigir directamente al panel (dashboard) del cliente
                    header("Location: index.php?url=cliente/dashboard");
                    exit;
                }
            }
            
            // Si ocurre algún fallo en la inserción o proceso, informar del error
            $_SESSION['error'] = "Error interno al registrar la cuenta";
            header("Location: index.php?url=register");
            exit;
        }
    }
}
