<?php
require_once __DIR__ . '/../config/database.php';

class ServicioModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerActivos(): array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM servicios WHERE activo = 1 ORDER BY nombre_servicio ASC");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en obtenerActivos: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerTodos(): array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM servicios ORDER BY nombre_servicio ASC");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en obtenerTodos: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPorId(int $id): ?array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM servicios WHERE id_servicio = ?");
            $stmt->execute([$id]);
            $resultado = $stmt->fetch();
            return $resultado ?: null;
        } catch (PDOException $e) {
            error_log("Error en obtenerPorId: " . $e->getMessage());
            return null;
        }
    }

    public function crear(array $datos): bool {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO servicios (nombre_servicio, descripcion, precio, duracion_minutos, imagen_url, activo) 
                VALUES (?, ?, ?, ?, ?, 1)
            ");
            return $stmt->execute([
                $datos['nombre_servicio'],
                $datos['descripcion'] ?? null,
                $datos['precio'],
                $datos['duracion_minutos'],
                $datos['imagen_url'] ?? null
            ]);
        } catch (PDOException $e) {
            error_log("Error en crear (Servicio): " . $e->getMessage());
            return false;
        }
    }

    public function actualizar(int $id, array $datos): bool {
        try {
            $stmt = $this->db->prepare("
                UPDATE servicios 
                SET nombre_servicio = ?, descripcion = ?, precio = ?, duracion_minutos = ?, imagen_url = ? 
                WHERE id_servicio = ?
            ");
            return $stmt->execute([
                $datos['nombre_servicio'],
                $datos['descripcion'] ?? null,
                $datos['precio'],
                $datos['duracion_minutos'],
                $datos['imagen_url'] ?? null,
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Error en actualizar (Servicio): " . $e->getMessage());
            return false;
        }
    }

    public function toggleActivo(int $id, int $nuevoEstado): bool {
        try {
            $stmt = $this->db->prepare("UPDATE servicios SET activo = ? WHERE id_servicio = ?");
            return $stmt->execute([$nuevoEstado, $id]);
        } catch (PDOException $e) {
            error_log("Error en toggleActivo (Servicio): " . $e->getMessage());
            return false;
        }
    }
}
