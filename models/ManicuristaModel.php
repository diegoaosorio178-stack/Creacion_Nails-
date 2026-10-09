<?php
require_once __DIR__ . '/../config/database.php';

class ManicuristaModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerActivas(): array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM manicuristas WHERE estado_laboral = 'activo' ORDER BY nombre ASC");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en obtenerActivas: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerTodas(): array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM manicuristas ORDER BY nombre ASC");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en obtenerTodas (Manicurista): " . $e->getMessage());
            return [];
        }
    }

    public function toggleEstadoLaboral(int $id, string $nuevoEstado): bool {
        try {
            // Validar que el estado sea correcto
            if (!in_array($nuevoEstado, ['activo', 'inactivo'])) {
                return false;
            }
            $stmt = $this->db->prepare("UPDATE manicuristas SET estado_laboral = ? WHERE id_manicurista = ?");
            return $stmt->execute([$nuevoEstado, $id]);
        } catch (PDOException $e) {
            error_log("Error en toggleEstadoLaboral: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerHorarios(int $idManicurista): array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM horarios WHERE id_manicurista = ? ORDER BY FIELD(dia_semana, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')");
            $stmt->execute([$idManicurista]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en obtenerHorarios: " . $e->getMessage());
            return [];
        }
    }

    public function asignarHorario(int $idManicurista, string $dia, string $inicio, string $fin): bool {
        try {
            // Buscar si ya existe el horario para ese día
            $stmt = $this->db->prepare("SELECT id_horario FROM horarios WHERE id_manicurista = ? AND dia_semana = ?");
            $stmt->execute([$idManicurista, $dia]);
            $existe = $stmt->fetch();

            if ($existe) {
                // Actualizar
                $update = $this->db->prepare("UPDATE horarios SET hora_inicio = ?, hora_fin = ?, activo = 1 WHERE id_horario = ?");
                return $update->execute([$inicio, $fin, $existe['id_horario']]);
            } else {
                // Insertar
                $insert = $this->db->prepare("INSERT INTO horarios (id_manicurista, dia_semana, hora_inicio, hora_fin, activo) VALUES (?, ?, ?, ?, 1)");
                return $insert->execute([$idManicurista, $dia, $inicio, $fin]);
            }
        } catch (PDOException $e) {
            error_log("Error en asignarHorario: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerManicuristaMenorCarga(string $fecha): int {
        try {
            $stmt = $this->db->prepare("
                SELECT m.id_manicurista, COUNT(c.id_cita) as total_citas 
                FROM manicuristas m 
                LEFT JOIN citas c ON m.id_manicurista = c.id_manicurista 
                                  AND c.fecha_cita = ? 
                                  AND c.estado IN ('pendiente', 'confirmada')
                WHERE m.estado_laboral = 'activo'
                GROUP BY m.id_manicurista
                ORDER BY total_citas ASC
                LIMIT 1
            ");
            $stmt->execute([$fecha]);
            $resultado = $stmt->fetch();
            return $resultado ? (int)$resultado['id_manicurista'] : 0;
        } catch (PDOException $e) {
            error_log("Error en obtenerManicuristaMenorCarga: " . $e->getMessage());
            return 0;
        }
    }
}
