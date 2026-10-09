<?php
require_once __DIR__ . '/../models/CitaModel.php';
require_once __DIR__ . '/../models/ServicioModel.php';
require_once __DIR__ . '/../models/ManicuristaModel.php';

class AdminController {
    private CitaModel $citaModel;
    private ServicioModel $servicioModel;
    private ManicuristaModel $manicuristaModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->verificarAcceso('administrador');
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
        // Fetch real metrics from the models
        $pendientesCount = count($this->citaModel->obtenerPendientesAuditoria());
        $citasHoyCount = $this->citaModel->obtenerCantidadCitasHoy();
        $manicuristasCount = count($this->manicuristaModel->obtenerActivas());
        $facturadoMes = $this->citaModel->obtenerFacturadoMes();
        
        $ultimasCitas = $this->citaModel->obtenerUltimasCitas(5);

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function auditarCitas() {
        $pendientes = $this->citaModel->obtenerPendientesAuditoria();
        require_once __DIR__ . '/../views/admin/auditar_citas.php';
    }

    public function aprobarCita() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idCita = filter_input(INPUT_POST, 'id_cita', FILTER_VALIDATE_INT);
            if ($idCita) {
                if ($this->citaModel->aprobarCita($idCita)) {
                    $_SESSION['success'] = "Cita confirmada correctamente.";
                } else {
                    $_SESSION['error'] = "Error al confirmar la cita.";
                }
            }
            $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?url=admin/auditarCitas';
            header("Location: $referer");
            exit;
        }
    }

    public function rechazarCita() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idCita = filter_input(INPUT_POST, 'id_cita', FILTER_VALIDATE_INT);
            $motivo = filter_input(INPUT_POST, 'motivo_rechazo', FILTER_SANITIZE_STRING);
            
            if ($idCita && !empty($motivo)) {
                if ($this->citaModel->rechazarCita($idCita, $motivo)) {
                    $_SESSION['success'] = "Cita rechazada.";
                } else {
                    $_SESSION['error'] = "Error al rechazar la cita.";
                }
            } else {
                $_SESSION['error'] = "El motivo de rechazo es obligatorio.";
            }
            $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?url=admin/auditarCitas';
            header("Location: $referer");
            exit;
        }
    }

    public function servicios() {
        $servicios = $this->servicioModel->obtenerTodos();
        require_once __DIR__ . '/../views/admin/servicios.php';
    }

    public function crearServicio() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $precioRaw = $_POST['precio'] ?? '0';
            $precioLimpio = str_replace(['.', ','], '', $precioRaw);

            $datos = [
                'nombre_servicio' => isset($_POST['nombre_servicio']) ? trim(htmlspecialchars($_POST['nombre_servicio'])) : '',
                'descripcion' => isset($_POST['descripcion']) ? trim(htmlspecialchars($_POST['descripcion'])) : null,
                'precio' => (float)$precioLimpio,
                'duracion_minutos' => filter_input(INPUT_POST, 'duracion_minutos', FILTER_VALIDATE_INT),
                'imagen_url' => filter_input(INPUT_POST, 'imagen_url', FILTER_SANITIZE_URL)
            ];

            if ($datos['nombre_servicio'] && $datos['precio'] > 0 && $datos['duracion_minutos'] > 0) {
                if ($this->servicioModel->crear($datos)) {
                    $_SESSION['success'] = "Servicio creado exitosamente.";
                } else {
                    $_SESSION['error'] = "Error al crear el servicio. Verifique que el nombre sea único.";
                }
            } else {
                $_SESSION['error'] = "Datos inválidos para crear servicio.";
            }
            header("Location: index.php?url=admin/servicios");
            exit;
        }
    }

    public function toggleServicio() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id_servicio', FILTER_VALIDATE_INT);
            $estado = filter_input(INPUT_POST, 'estado_nuevo', FILTER_VALIDATE_INT);
            
            if ($id && $estado !== false) {
                if ($this->servicioModel->toggleActivo($id, $estado)) {
                    $_SESSION['success'] = "Estado del servicio actualizado.";
                } else {
                    $_SESSION['error'] = "Error al actualizar estado.";
                }
            }
            header("Location: index.php?url=admin/servicios");
            exit;
        }
    }

    public function actualizarPrecioServicio() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id_servicio', FILTER_VALIDATE_INT);
            $precioRaw = $_POST['precio_nuevo'] ?? '0';
            $precioLimpio = str_replace(['.', ','], '', $precioRaw);
            $precio = (float)$precioLimpio;

            if ($id && $precio > 0) {
                // Obtener el servicio actual para mantener los demas datos
                $servicioActual = $this->servicioModel->obtenerPorId($id);
                if ($servicioActual) {
                    $servicioActual['precio'] = $precio;
                    if ($this->servicioModel->actualizar($id, $servicioActual)) {
                        $_SESSION['success'] = "Precio actualizado correctamente.";
                    } else {
                        $_SESSION['error'] = "Error al actualizar el precio.";
                    }
                }
            } else {
                $_SESSION['error'] = "Datos inválidos para actualizar el precio.";
            }
            header("Location: index.php?url=admin/servicios");
            exit;
        }
    }

    public function manicuristas() {
        $manicuristas = $this->manicuristaModel->obtenerTodas();
        require_once __DIR__ . '/../views/admin/gusuarios.php';
    }

    public function toggleManicurista() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id_manicurista', FILTER_VALIDATE_INT);
            $estado = filter_input(INPUT_POST, 'estado_nuevo', FILTER_SANITIZE_STRING);
            
            if ($id && in_array($estado, ['activa', 'inactiva'])) {
                if ($this->manicuristaModel->toggleEstadoLaboral($id, $estado)) {
                    $_SESSION['success'] = "Estado laboral actualizado.";
                } else {
                    $_SESSION['error'] = "Error al actualizar estado.";
                }
            }
            header("Location: index.php?url=admin/manicuristas");
            exit;
        }
    }

    public function asignarHorario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id_manicurista', FILTER_VALIDATE_INT);
            $dia = filter_input(INPUT_POST, 'dia_semana', FILTER_SANITIZE_STRING);
            $inicio = filter_input(INPUT_POST, 'hora_inicio', FILTER_SANITIZE_STRING);
            $fin = filter_input(INPUT_POST, 'hora_fin', FILTER_SANITIZE_STRING);

            if ($id && $dia && $inicio && $fin) {
                if ($this->manicuristaModel->asignarHorario($id, $dia, $inicio, $fin)) {
                    $_SESSION['success'] = "Horario asignado exitosamente.";
                } else {
                    $_SESSION['error'] = "Error al asignar horario.";
                }
            } else {
                $_SESSION['error'] = "Faltan datos para asignar el horario.";
            }
            header("Location: index.php?url=admin/manicuristas");
            exit;
        }
    }

    public function liquidacion() {
        // Por defecto tomar la quincena actual basado en el día de hoy
        $diaActual = (int)date('d');
        $quincenaDefault = ($diaActual <= 15) ? 1 : 2;
        
        $quincena = filter_input(INPUT_GET, 'quincena', FILTER_VALIDATE_INT) ?: $quincenaDefault;
        
        $anioActual = date('Y');
        $mesActual = date('m');
        
        if ($quincena === 1) {
            $inicio = "$anioActual-$mesActual-01";
            $fin = "$anioActual-$mesActual-15";
        } else {
            $inicio = "$anioActual-$mesActual-16";
            $fin = date("Y-m-t", strtotime($inicio));
        }

        $reporte = $this->citaModel->reporteLiquidacionGeneral($inicio, $fin);
        
        // Nombres de meses en español para la vista
        $meses = ['01'=>'Enero', '02'=>'Febrero', '03'=>'Marzo', '04'=>'Abril', '05'=>'Mayo', '06'=>'Junio', '07'=>'Julio', '08'=>'Agosto', '09'=>'Septiembre', '10'=>'Octubre', '11'=>'Noviembre', '12'=>'Diciembre'];
        $nombreMes = $meses[$mesActual];
        
        require_once __DIR__ . '/../views/admin/liquidacion.php';
    }
}
