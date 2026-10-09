<?php
require_once __DIR__ . '/../models/CitaModel.php';
require_once __DIR__ . '/../models/ServicioModel.php';
require_once __DIR__ . '/../models/ManicuristaModel.php';

class CitaController {
    private CitaModel $citaModel;
    private ServicioModel $servicioModel;
    private ManicuristaModel $manicuristaModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->verificarAcceso('cliente');
        $this->citaModel = new CitaModel();
        $this->servicioModel = new ServicioModel();
        $this->manicuristaModel = new ManicuristaModel();
    }

    private function verificarAcceso(string $rolEsperado) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== $rolEsperado) {
            header("Location: index.php?url=auth/login");
            exit;
        }
    }

    public function dashboard() {
        $this->historial();
    }

    public function historial() {
        $citas = $this->citaModel->obtenerPorCliente($_SESSION['user']['id']);
        require_once __DIR__ . '/../views/cliente/dashboard.php';
    }

    public function agendar() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $serviciosActivos = $this->servicioModel->obtenerActivos();
            $manicuristasActivas = $this->manicuristaModel->obtenerActivas();
            require_once __DIR__ . '/../views/cliente/agendar.php';
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idServicio = filter_input(INPUT_POST, 'id_servicio', FILTER_VALIDATE_INT);
            $idManicurista = filter_input(INPUT_POST, 'id_manicurista', FILTER_VALIDATE_INT);
            $fechaCita = filter_input(INPUT_POST, 'fecha_cita', FILTER_SANITIZE_STRING);
            $horaInicio = filter_input(INPUT_POST, 'hora_cita', FILTER_SANITIZE_STRING);
            $montoAbono = filter_input(INPUT_POST, 'monto_abono', FILTER_VALIDATE_FLOAT);

            if (!$idServicio || !$fechaCita || !$horaInicio || $montoAbono === false) {
                $_SESSION['error'] = "Faltan datos requeridos para la cita.";
                header("Location: index.php?url=cliente/agendar");
                exit;
            }

            // Asignación automática
            if ($idManicurista === 0 || $idManicurista === false) {
                $idManicuristaAsignado = $this->manicuristaModel->obtenerManicuristaMenorCarga($fechaCita);
                if ($idManicuristaAsignado === 0) {
                    $_SESSION['error'] = "No hay manicuristas disponibles para esa fecha.";
                    header("Location: index.php?url=cliente/agendar");
                    exit;
                }
                $idManicurista = $idManicuristaAsignado;
            }

            // Validación de Comprobante
            if (!isset($_FILES['comprobante']) || $_FILES['comprobante']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['error'] = "El comprobante de abono es OBLIGATORIO.";
                header("Location: index.php?url=cliente/agendar");
                exit;
            }

            $file = $_FILES['comprobante'];
            if ($file['size'] > 5242880) { // 5MB limit
                $_SESSION['error'] = "El comprobante no debe superar los 5MB.";
                header("Location: index.php?url=cliente/agendar");
                exit;
            }

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $permitidas = ['jpg', 'jpeg', 'png', 'pdf'];
            if (!in_array($ext, $permitidas)) {
                $_SESSION['error'] = "Formato de archivo inválido. Solo JPG, PNG o PDF.";
                header("Location: index.php?url=cliente/agendar");
                exit;
            }

            $uploadDir = __DIR__ . '/../public/uploads/comprobantes/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = uniqid('comp_', true) . '.' . $ext;
            $destPath = $uploadDir . $fileName;

            if (!move_uploaded_file($file['tmp_name'], $destPath)) {
                $_SESSION['error'] = "Error al subir el comprobante. Intente nuevamente.";
                header("Location: index.php?url=cliente/agendar");
                exit;
            }

            $servicioData = $this->servicioModel->obtenerPorId($idServicio);
            $precioCobrado = $servicioData ? $servicioData['precio'] : 0;
            $duracion = $servicioData ? $servicioData['duracion_minutos'] : 60;
            
            // Calcular hora de fin sumando la duración en minutos
            $horaFinObj = DateTime::createFromFormat('H:i:s', $horaInicio) ?: DateTime::createFromFormat('H:i', $horaInicio);
            if ($horaFinObj) {
                $horaFinObj->add(new DateInterval('PT' . $duracion . 'M'));
                $horaFin = $horaFinObj->format('H:i:s');
            } else {
                $horaFin = $horaInicio;
            }

            $citaData = [
                'id_cliente' => $_SESSION['user']['id'],
                'id_manicurista' => $idManicurista,
                'id_servicio' => $idServicio,
                'fecha_cita' => $fechaCita,
                'hora_inicio' => $horaInicio,
                'hora_fin' => $horaFin,
                'precio_cobrado' => $precioCobrado
            ];

            $pagoData = [
                'monto_abono' => $montoAbono,
                'comprobante_url' => 'public/uploads/comprobantes/' . $fileName,
                'metodo_pago' => 'transferencia'
            ];

            if ($this->citaModel->crearCitaConPago($citaData, $pagoData)) {
                $_SESSION['success'] = "Cita agendada exitosamente y pendiente de validación.";
                header("Location: index.php?url=cliente/dashboard");
                exit;
            } else {
                $_SESSION['error'] = "Error al procesar la cita en la base de datos.";
                header("Location: index.php?url=cliente/agendar");
                exit;
            }
        }
    }

    public function calificar() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $idCita = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            // Mostraríamos form
            require_once __DIR__ . '/../views/cliente/calificar.php';
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idCita = filter_input(INPUT_POST, 'id_cita', FILTER_VALIDATE_INT);
            $estrellas = filter_input(INPUT_POST, 'estrellas', FILTER_VALIDATE_INT);
            $comentario = filter_input(INPUT_POST, 'comentario', FILTER_SANITIZE_STRING);

            if ($idCita && $estrellas && $estrellas >= 1 && $estrellas <= 5) {
                // El id de la manicurista se podría buscar, usamos 1 como mock si no llega por POST
                $idManicurista = filter_input(INPUT_POST, 'id_manicurista', FILTER_VALIDATE_INT) ?: 1;

                if ($this->citaModel->guardarCalificacion($idCita, $idManicurista, $estrellas, $comentario)) {
                    $_SESSION['success'] = "¡Gracias por tu calificación!";
                } else {
                    $_SESSION['error'] = "Error al guardar calificación o la cita no está completada.";
                }
            } else {
                $_SESSION['error'] = "Datos de calificación inválidos.";
            }

            header("Location: index.php?url=cliente/dashboard");
            exit;
        }
    }
}
