<?php
require '../includes/db_connection.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $order_id = $_GET['id'];

    try {
        // Iniciar transacción para asegurar integridad de datos
        $conn->beginTransaction();

        // Verificar que la orden existe antes de eliminar
        $checkStmt = $conn->prepare("SELECT Order_ID FROM Orders WHERE Order_ID = ?");
        $checkStmt->execute([$order_id]);

        if ($checkStmt->rowCount() == 0) {
            // La orden no existe
            header("Location: index.php?error=order_not_found");
            exit;
        }

        // Primero eliminar todos los detalles de la orden
        // (Esto se hace automáticamente por la restricción CASCADE en la base de datos,
        // pero lo hacemos explícitamente para mayor control)
        $deleteDetailsStmt = $conn->prepare("DELETE FROM OrderDetails WHERE Order_ID = ?");
        $deleteDetailsStmt->execute([$order_id]);

        // Luego eliminar la orden
        $deleteOrderStmt = $conn->prepare("DELETE FROM Orders WHERE Order_ID = ?");
        $deleteOrderStmt->execute([$order_id]);

        // Confirmar la transacción
        $conn->commit();

        // Redirigir con mensaje de éxito
        header("Location: index.php?success=deleted");
        exit;

    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollBack();

        // Redirigir con mensaje de error
        header("Location: index.php?error=delete_failed");
        exit;
    }
} else {
    // No se proporcionó ID válido
    header("Location: index.php?error=no_id");
    exit;
}
?>
