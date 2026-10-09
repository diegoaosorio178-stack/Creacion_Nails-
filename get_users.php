<?php
require_once __DIR__ . '/config/database.php';
try {
    $db = Database::getConnection();
    
    // Reset Admin password to 123456
    $newHash = password_hash('123456', PASSWORD_BCRYPT);
    $db->query("UPDATE administradores SET password_hash = '$newHash' WHERE email = 'admin@creacionnails.com'");
    
    echo "Admin password reset to '123456'\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

