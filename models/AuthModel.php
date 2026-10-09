<?php
require_once __DIR__ . '/../config/database.php';

class AuthModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function emailExiste(string $email): bool {
        try {
            // Verificar en clientes
            $stmt = $this->db->prepare("SELECT id_cliente FROM clientes WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) return true;

            // Verificar en manicuristas
            $stmt = $this->db->prepare("SELECT id_manicurista FROM manicuristas WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) return true;

            // Verificar en administradores
            $stmt = $this->db->prepare("SELECT id_administrador FROM administradores WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) return true;

            return false;
        } catch (PDOException $e) {
            error_log("Error en emailExiste: " . $e->getMessage());
            return true; // Asumir existe por seguridad en caso de fallo
        }
    }

    public function registrarCliente(array $datos): bool {
        try {
            $hash = password_hash($datos['password'], PASSWORD_BCRYPT);
            $stmt = $this->db->prepare("
                INSERT INTO clientes (nombre, email, password_hash, telefono, activo, fecha_registro) 
                VALUES (?, ?, ?, ?, 1, NOW())
            ");
            return $stmt->execute([
                $datos['nombre'],
                $datos['email'],
                $hash,
                $datos['telefono']
            ]);
        } catch (PDOException $e) {
            error_log("Error en registrarCliente: " . $e->getMessage());
            return false;
        }
    }

    public function loginCascada(string $email, string $password): ?array {
        try {
            // 1. Administrador
            $stmt = $this->db->prepare("SELECT * FROM administradores WHERE email = ?");
            $stmt->execute([$email]);
            if ($admin = $stmt->fetch()) {
                if (password_verify($password, $admin['password_hash'])) {
                    $admin['rol'] = 'administrador';
                    return $admin;
                }
                return null; // Contraseña incorrecta
            }
            
            // 2. Manicurista
            $stmt = $this->db->prepare("SELECT * FROM manicuristas WHERE email = ?");
            $stmt->execute([$email]);
            if ($manicurista = $stmt->fetch()) {
                if ($manicurista['estado_laboral'] === 'activo' && password_verify($password, $manicurista['password_hash'])) {
                    $manicurista['rol'] = 'manicurista';
                    return $manicurista;
                }
                return null; 
            }

            // 3. Cliente
            $stmt = $this->db->prepare("SELECT * FROM clientes WHERE email = ?");
            $stmt->execute([$email]);
            if ($cliente = $stmt->fetch()) {
                if ($cliente['activo'] == 1 && password_verify($password, $cliente['password_hash'])) {
                    $cliente['rol'] = 'cliente';
                    return $cliente;
                }
                return null;
            }

            return null; // Email no encontrado
            
        } catch (PDOException $e) {
            error_log("Error en loginCascada: " . $e->getMessage());
            return null;
        }
    }
}
