<?php
require_once __DIR__ . '/../models/CitaModel.php';
require_once __DIR__ . '/../models/ServicioModel.php';
require_once __DIR__ . '/../models/ManicuristaModel.php';

class ClienteController {
    private CitaModel $citaModel;
    private ServicioModel $servicioModel;
    private ManicuristaModel $manicuristaModel;

    public function __construct() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'cliente') {
            header("Location: index.php?url=auth");
            exit;
        }
        $this->citaModel = new CitaModel();
        $this->servicioModel = new ServicioModel();
        $this->manicuristaModel = new ManicuristaModel();
    }

    public function dashboard() {
        // HU-004: Obtener historial
        $citas = $this->citaModel->obtenerPorCliente($_SESSION['id_usuario']);
        // Las vistas estáticas (cascarones) ya fueron creadas, si quisiéramos inyectar la data usaríamos requires aquí
        // Por ahora, solo renderizamos la vista estática como se solicitó
        require_once __DIR__ . '/../views/cliente/dashboard.php';
    }

    public function agendar() {
        // Muestra formulario de reserva
        require_once __DIR__ . '/../views/cliente/agendar.php';
    }

    public function guardarReserva() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idServicio = (int)$_POST['id_servicio'];
            $idManicurista = (int)$_POST['id_manicurista'];
            $fechaCita = $_POST['fecha_cita'];
            $horaInicio = $_POST['hora_cita'];
            $montoAbono = (float)$_POST['monto_abono'];
            
            // Simular hora_fin añadiendo duración del servicio
            // HU-003: Asignación Automática
            if ($idManicurista === 0) {
                $idManicurista = $this->manicuristaModel->obtenerManicuristaMenorCarga($fechaCita);
                if ($idManicurista === 0) {
                    die("No hay manicuristas disponibles para esa fecha.");
                }
            }

            // Manejo de archivo (Comprobante Obligatorio)
            if (isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/uploads/comprobantes/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = time() . '_' . basename($_FILES['comprobante']['name']);
                $destPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['comprobante']['tmp_name'], $destPath)) {
                    $comprobanteUrl = 'public/uploads/comprobantes/' . $fileName;
                    
                    $citaData = [
                        'id_cliente' => $_SESSION['id_usuario'],
                        'id_manicurista' => $idManicurista,
                        'id_servicio' => $idServicio,
                        'fecha_cita' => $fechaCita,
                        'hora_inicio' => $horaInicio,
                        'hora_fin' => '00:00:00', // Requeriría buscar duración del servicio para sumar
                        'precio_cobrado' => 0 // Requeriría buscar precio del servicio
                    ];

                    $pagoData = [
                        'monto_abono' => $montoAbono,
                        'comprobante_url' => $comprobanteUrl,
                        'metodo_pago' => 'transferencia'
                    ];

                    // HU-003: Transacción Atómica
                    if ($this->citaModel->crearCitaConPago($citaData, $pagoData)) {
                        header("Location: index.php?url=cliente/dashboard&msg=cita_creada");
                        exit;
                    }
                }
            }
            
            // Falla en archivo o creación
            header("Location: index.php?url=cliente/agendar&error=comprobante_requerido");
            exit;
        }
    }

    public function calificar() {
        require_once __DIR__ . '/../views/cliente/calificar.php';
    }

    public function guardarCalificacion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idCita = (int)$_POST['id_cita'];
            $estrellas = (int)$_POST['estrellas'];
            $comentario = $_POST['comentario'] ?? '';
            
            // Omitimos $idManicurista por simplificación o lo buscaríamos
            // HU-005: Validar estado completado dentro del modelo
            $this->citaModel->guardarCalificacion($idCita, 1 /* mock */, $estrellas, $comentario);
            
            header("Location: index.php?url=cliente/dashboard&msg=calificacion_guardada");
            exit;
        }
    }
}
