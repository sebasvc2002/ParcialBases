<?php

// db_connection.php

$servername = "mydigitalinnovation.com"; // Generalmente es 'localhost' en cPanel
$username = "database_user"; // El usuario que creaste en el Paso 1
$password = "Sebasvc1236117$"; // La contraseña que creaste en el Paso 1
$dbname = "ventasdb"; // La base de datos que creaste en el Paso 1

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Configurar el modo de error de PDO a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
