<?php
require '../includes/db_connection.php';

if (isset($_GET['id'])) {
    $employee_id = $_GET['id'];

    try {
        $sql = "DELETE FROM Employees WHERE Employee_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$employee_id]);
    } catch (PDOException $e) {
        die("Error al eliminar al empleado: " . $e->getMessage());
    }

    // Redirigir de vuelta a la lista
    header("Location: index.php");
    exit();
} else {
    // Si no se proporciona ID, volver a la lista
    header("Location: index.php");
    exit();
}
?>