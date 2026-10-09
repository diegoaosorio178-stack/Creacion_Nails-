<?php
require_once __DIR__ . '/../config/database.php';

class CitaModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function crearCitaConPago(array $cita, array $pago): bool {
        try {
            $this->db->beginTransaction();

            // Insertar cita
            $stmtCita = $this->db->prepare("
                INSERT INTO citas (id_cliente, id_manicurista, id_servicio, fecha_cita, hora_inicio, hora_fin, precio_cobrado, estado, fecha_solicitud)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pendiente', NOW())
            ");
            $stmtCita->execute([
                $cita['id_cliente'],
                $cita['id_manicurista'],
                $cita['id_servicio'],
                $cita['fecha_cita'],
                $cita['hora_inicio'],
                $cita['hora_fin'],
                $cita['precio_cobrado']
            ]);
            
            $idCita = $this->db->lastInsertId();

            // Insertar pago vinculado
            $stmtPago = $this->db->prepare("
                INSERT INTO pagos (id_cita, monto_abono, comprobante_url, metodo_pago, estado_pago, fecha_pago)
                VALUES (?, ?, ?, ?, 'pendiente_validacion', NOW())
            ");
            $stmtPago->execute([
                $idCita,
                $pago['monto_abono'],
                $pago['comprobante_url'],
                $pago['metodo_pago']
            ]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error en crearCitaConPago: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerPorCliente(int $idCliente): array {
        try {
            $stmt = $this->db->prepare("
                SELECT c.*, s.nombre_servicio, m.nombre as nombre_manicurista 
                FROM citas c
                JOIN servicios s ON c.id_servicio = s.id_servicio
                JOIN manicuristas m ON c.id_manicurista = m.id_manicurista
                WHERE c.id_cliente = ?
                ORDER BY c.fecha_cita DESC, c.hora_inicio DESC
            ");
            $stmt->execute([$idCliente]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en obtenerPorCliente: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPendientesAuditoria(): array {
        try {
            $stmt = $this->db->prepare("
                SELECT c.*, cl.nombre as nombre_cliente, cl.telefono as telefono_cliente, 
                       s.nombre_servicio, m.nombre as nombre_manicurista, 
                       p.monto_abono, p.comprobante_url 
                FROM citas c
                JOIN clientes cl ON c.id_cliente = cl.id_cliente
                JOIN servicios s ON c.id_servicio = s.id_servicio
                JOIN manicuristas m ON c.id_manicurista = m.id_manicurista
                JOIN pagos p ON c.id_cita = p.id_cita
                WHERE c.estado = 'pendiente'
                ORDER BY c.fecha_solicitud ASC
            ");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en obtenerPendientesAuditoria: " . $e->getMessage());
            return [];
        }
    }

    public function aprobarCita(int $idCita): bool {
        try {
            $this->db->beginTransaction();

            $stmtCita = $this->db->prepare("UPDATE citas SET estado = 'confirmada' WHERE id_cita = ?");
            $stmtCita->execute([$idCita]);

            $stmtPago = $this->db->prepare("UPDATE pagos SET estado_pago = 'aprobado' WHERE id_cita = ?");
            $stmtPago->execute([$idCita]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error en aprobarCita: " . $e->getMessage());
            return false;
        }
    }

    public function rechazarCita(int $idCita, string $motivo): bool {
        try {
            $this->db->beginTransaction();

            $stmtCita = $this->db->prepare("UPDATE citas SET estado = 'rechazada', motivo_rechazo = ? WHERE id_cita = ?");
            $stmtCita->execute([$motivo, $idCita]);

            $stmtPago = $this->db->prepare("UPDATE pagos SET estado_pago = 'rechazado' WHERE id_cita = ?");
            $stmtPago->execute([$idCita]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error en rechazarCita: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerAgendaManicurista(int $idManicurista, ?string $fecha = null): array {
        try {
            $query = "
                SELECT c.*, cl.nombre as nombre_cliente, cl.telefono as telefono_cliente, s.nombre_servicio 
                FROM citas c
                JOIN clientes cl ON c.id_cliente = cl.id_cliente
                JOIN servicios s ON c.id_servicio = s.id_servicio
                WHERE c.id_manicurista = ? AND c.estado = 'confirmada'
            ";
            
            $params = [$idManicurista];
            if ($fecha !== null) {
                $query .= " AND c.fecha_cita = ?";
                $params[] = $fecha;
            }
            
            $query .= " ORDER BY c.fecha_cita ASC, c.hora_inicio ASC";

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en obtenerAgendaManicurista: " . $e->getMessage());
            return [];
        }
    }

    public function finalizarServicio(int $idCita): bool {
        try {
            $stmt = $this->db->prepare("UPDATE citas SET estado = 'completada', fecha_finalizacion = NOW() WHERE id_cita = ?");
            return $stmt->execute([$idCita]);
        } catch (PDOException $e) {
            error_log("Error en finalizarServicio: " . $e->getMessage());
            return false;
        }
    }

    public function calcularComisionesManicurista(int $idManicurista, string $inicio, string $fin): array {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(id_cita) as citas_completadas, SUM(precio_cobrado) as total_facturado, (SUM(precio_cobrado) * 0.50) as comision 
                FROM citas 
                WHERE id_manicurista = ? 
                AND estado = 'completada' 
                AND fecha_cita BETWEEN ? AND ?
            ");
            $stmt->execute([$idManicurista, $inicio, $fin]);
            $resultado = $stmt->fetch();
            
            return [
                'citas_completadas' => (int)($resultado['citas_completadas'] ?? 0),
                'total_facturado' => (float)($resultado['total_facturado'] ?? 0),
                'comision' => (float)($resultado['comision'] ?? 0)
            ];
        } catch (PDOException $e) {
            error_log("Error en calcularComisionesManicurista: " . $e->getMessage());
            return ['citas_completadas' => 0, 'total_facturado' => 0, 'comision' => 0];
        }
    }

    public function reporteLiquidacionGeneral(string $inicio, string $fin): array {
        try {
            $stmt = $this->db->prepare("
                SELECT m.nombre as manicurista, COUNT(c.id_cita) as citas_completadas, SUM(c.precio_cobrado) as facturado, (SUM(c.precio_cobrado) * 0.50) as pago_manicurista, (SUM(c.precio_cobrado) * 0.50) as retencion_spa
                FROM citas c
                JOIN manicuristas m ON c.id_manicurista = m.id_manicurista
                WHERE c.estado = 'completada' 
                AND c.fecha_cita BETWEEN ? AND ?
                GROUP BY m.id_manicurista
                ORDER BY facturado DESC
            ");
            $stmt->execute([$inicio, $fin]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en reporteLiquidacionGeneral: " . $e->getMessage());
            return [];
        }
    }

    public function guardarCalificacion(int $idCita, int $idManicurista, int $estrellas, string $comentario): bool {
        try {
            // Verificar que la cita está completada
            $verificar = $this->db->prepare("SELECT estado FROM citas WHERE id_cita = ?");
            $verificar->execute([$idCita]);
            $cita = $verificar->fetch();
            
            if (!$cita || $cita['estado'] !== 'completada') {
                return false;
            }

            $stmt = $this->db->prepare("
                INSERT INTO calificaciones (id_cita, id_manicurista, estrellas, comentario, fecha_calificacion) 
                VALUES (?, ?, ?, ?, NOW())
            ");
            return $stmt->execute([$idCita, $idManicurista, $estrellas, $comentario]);
        } catch (PDOException $e) {
            error_log("Error en guardarCalificacion: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerCantidadCitasHoy(): int {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM citas WHERE fecha_cita = CURDATE()");
            $stmt->execute();
            $resultado = $stmt->fetch();
            return (int)($resultado['total'] ?? 0);
        } catch (PDOException $e) {
            error_log("Error en obtenerCantidadCitasHoy: " . $e->getMessage());
            return 0;
        }
    }

    public function obtenerFacturadoMes(): float {
        try {
            $stmt = $this->db->prepare("
                SELECT SUM(precio_cobrado) as total 
                FROM citas 
                WHERE estado = 'completada' 
                AND MONTH(fecha_cita) = MONTH(CURDATE()) 
                AND YEAR(fecha_cita) = YEAR(CURDATE())
            ");
            $stmt->execute();
            $resultado = $stmt->fetch();
            return (float)($resultado['total'] ?? 0);
        } catch (PDOException $e) {
            error_log("Error en obtenerFacturadoMes: " . $e->getMessage());
            return 0.0;
        }
    }

    public function obtenerUltimasCitas(int $limite = 5): array {
        try {
            $stmt = $this->db->prepare("
                SELECT c.*, cl.nombre as nombre_cliente, s.nombre_servicio, m.nombre as nombre_manicurista 
                FROM citas c
                JOIN clientes cl ON c.id_cliente = cl.id_cliente
                JOIN servicios s ON c.id_servicio = s.id_servicio
                JOIN manicuristas m ON c.id_manicurista = m.id_manicurista
                ORDER BY c.fecha_solicitud DESC
                LIMIT ?
            ");
            // Binding manual para PDO LIMIT
            $stmt->bindValue(1, $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en obtenerUltimasCitas: " . $e->getMessage());
            return [];
        }
    }
}
