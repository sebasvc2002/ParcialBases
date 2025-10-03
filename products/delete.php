<?php
require '../includes/db_connection.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    try {
        $sql = "DELETE FROM Products WHERE Product_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$product_id]);
    } catch (PDOException $e) {
        die("Error al eliminar el producto: " . $e->getMessage());
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