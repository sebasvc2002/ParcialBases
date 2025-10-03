<?php

// db_connection.php

$servername = "hostname"; 
$username = "database_user"; 
$password = "password"; 
$dbname = "ventasdb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
