<?php
require_once __DIR__ . '/config/database.php';
try {
    $db = Database::getConnection();
    $stmt = $db->query("DESCRIBE calificaciones");
    $schema = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($schema);
    
    $stmt2 = $db->query("SELECT * FROM calificaciones");
    $data = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    echo "\nData:\n";
    print_r($data);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
