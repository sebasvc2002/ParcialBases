<?php
require '../includes/db_connection.php';

if (isset($_GET['id'])) {
    $customer_id = $_GET['id'];

    try {
        $sql = "DELETE FROM Customers WHERE Customer_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$customer_id]);
    } catch (PDOException $e) {
        die("Error al eliminar la categoría: " . $e->getMessage());
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