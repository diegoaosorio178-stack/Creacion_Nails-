<?php
// Se incluye el modelo de citas necesario para acceder a los datos
require_once __DIR__ . '/../models/CitaModel.php';

/**
 * Clase ManicuristaController
 * Controlador que gestiona las vistas y acciones específicas del rol de manicurista.
 */
class ManicuristaController {
    // Propiedad privada para manejar las operaciones con la tabla de citas
    private CitaModel $citaModel;

    /**
     * Constructor
     * Inicia la sesión si no está iniciada, verifica el acceso del usuario
     * y crea una instancia de CitaModel.
     */
    public function __construct() {
        // Iniciar sesión si no hay una activa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Verificar que el usuario sea una manicurista
        $this->verificarAcceso('manicurista');
        // Instanciar el modelo de citas
        $this->citaModel = new CitaModel();
    }

    /**
     * Método privado verificarAcceso
     * Comprueba que la sesión tenga un usuario válido con el rol esperado.
     * Si no cumple la condición, redirige al login.
     * 
     * @param string $rolEsperado El rol que se debe tener para acceder.
     */
    private function verificarAcceso(string $rolEsperado) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== $rolEsperado) {
            header("Location: index.php?url=auth/login");
            exit;
        }
    }

    /**
     * Método dashboard
     * Carga y muestra el panel de control de la manicurista, 
     * incluyendo sus citas (diarias o totales) y el cálculo de sus comisiones en la quincena actual.
     */
    public function dashboard() {
        // Obtener ID del usuario desde la sesión
        $idManicurista = $_SESSION['user']['id'];
        $fechaHoy = date('Y-m-d');
        // Verificar si hay un filtro de vista en la URL (diaria o todas), por defecto: todas
        $vistaGet = $_GET['vista'] ?? 'todas';
        $vista = htmlspecialchars($vistaGet);
        
        // Obtener citas dependiendo de la vista solicitada
        if ($vista === 'diaria') {
            $citas = $this->citaModel->obtenerAgendaManicurista($idManicurista, $fechaHoy);
        } else {
            $citas = $this->citaModel->obtenerAgendaManicurista($idManicurista);
        }

        // --- Lógica para determinar la Quincena Actual ---
        $diaActual = (int)date('d');
        $mesActual = date('m');
        $anioActual = date('Y');
        
        // Si estamos en la primera mitad del mes (días 1 al 15)
        if ($diaActual <= 15) {
            $inicioQuincena = "$anioActual-$mesActual-01";
            $finQuincena = "$anioActual-$mesActual-15";
        } else {
            // Si estamos en la segunda mitad del mes (día 16 hasta el final del mes)
            $inicioQuincena = "$anioActual-$mesActual-16";
            // t = número de días del mes dado
            $finQuincena = date("Y-m-t", strtotime($inicioQuincena));
        }

        // Calcular las comisiones de la manicurista en el rango de fechas de la quincena actual
        $comisiones = $this->citaModel->calcularComisionesManicurista($idManicurista, $inicioQuincena, $finQuincena);

        // Cargar la vista correspondiente pasando las variables necesarias
        require_once __DIR__ . '/../views/manicurista/dashboard.php';
    }

    /**
     * Método finalizarServicio
     * Recibe una petición POST para marcar una cita como finalizada, 
     * generando la comisión correspondiente si el modelo lo permite.
     */
    public function finalizarServicio() {
        // Validar que la petición se envíe mediante POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Filtrar y obtener el ID de la cita
            $idCita = filter_input(INPUT_POST, 'id_cita', FILTER_VALIDATE_INT);
            
            if ($idCita) {
                // En un entorno de producción, aquí deberíamos validar que la cita pertenece a este manicurista
                // Llamar al modelo para finalizar el servicio y generar comisión
                if ($this->citaModel->finalizarServicio($idCita)) {
                    $_SESSION['success'] = "Servicio finalizado con éxito. ¡Comisión generada!";
                } else {
                    $_SESSION['error'] = "No se pudo finalizar el servicio. Verifica que esté en estado 'confirmada'.";
                }
            } else {
                $_SESSION['error'] = "ID de cita inválido.";
            }

            // Redirigir de vuelta al dashboard de la manicurista
            header("Location: index.php?url=manicurista/dashboard");
            exit;
        }
    }
}
