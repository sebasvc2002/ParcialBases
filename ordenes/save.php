<?php
require '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Iniciar transacción para asegurar integridad de datos
        $conn->beginTransaction();

        $customer_id = $_POST['customer_id'];
        $employee_id = $_POST['employee_id'];
        $order_date = $_POST['order_date'];
        $order_id = !empty($_POST['order_id']) ? $_POST['order_id'] : null;

        if ($order_id) {
            // MODIFICAR ORDEN EXISTENTE

            // Actualizar información básica de la orden
            $stmt = $conn->prepare("UPDATE Orders SET Customer_ID = ?, Employee_ID = ?, OrderDate = ? WHERE Order_ID = ?");
            $stmt->execute([$customer_id, $employee_id, $order_date, $order_id]);

            // Obtener los IDs de detalles existentes para saber cuáles eliminar
            $existingDetailsStmt = $conn->prepare("SELECT OrderDetail_ID FROM OrderDetails WHERE Order_ID = ?");
            $existingDetailsStmt->execute([$order_id]);
            $existingDetailIds = $existingDetailsStmt->fetchAll(PDO::FETCH_COLUMN);

            $processedDetailIds = [];

            // Procesar productos en el formulario
            if (isset($_POST['products']) && is_array($_POST['products'])) {
                foreach ($_POST['products'] as $detail_key => $product_data) {
                    $product_id = $product_data['product_id'];
                    $quantity = $product_data['quantity'];

                    if (strpos($detail_key, 'new_') === 0) {
                        // PRODUCTO NUEVO - Insertar
                        $insertStmt = $conn->prepare("INSERT INTO OrderDetails (Order_ID, Product_ID, Quantity) VALUES (?, ?, ?)");
                        $insertStmt->execute([$order_id, $product_id, $quantity]);
                    } else {
                        // PRODUCTO EXISTENTE - Actualizar
                        $updateStmt = $conn->prepare("UPDATE OrderDetails SET Product_ID = ?, Quantity = ? WHERE OrderDetail_ID = ?");
                        $updateStmt->execute([$product_id, $quantity, $detail_key]);
                        $processedDetailIds[] = $detail_key;
                    }
                }
            }

            // Eliminar detalles que ya no están en el formulario
            $detailsToDelete = array_diff($existingDetailIds, $processedDetailIds);
            foreach ($detailsToDelete as $detailId) {
                $deleteStmt = $conn->prepare("DELETE FROM OrderDetails WHERE OrderDetail_ID = ?");
                $deleteStmt->execute([$detailId]);
            }

        } else {
            // CREAR NUEVA ORDEN

            // Insertar nueva orden
            $stmt = $conn->prepare("INSERT INTO Orders (Customer_ID, Employee_ID, OrderDate) VALUES (?, ?, ?)");
            $stmt->execute([$customer_id, $employee_id, $order_date]);
            $order_id = $conn->lastInsertId();

            // Insertar detalles de la orden
            if (isset($_POST['products']) && is_array($_POST['products'])) {
                foreach ($_POST['products'] as $product_data) {
                    $product_id = $product_data['product_id'];
                    $quantity = $product_data['quantity'];

                    $insertDetailStmt = $conn->prepare("INSERT INTO OrderDetails (Order_ID, Product_ID, Quantity) VALUES (?, ?, ?)");
                    $insertDetailStmt->execute([$order_id, $product_id, $quantity]);
                }
            }
        }

        // Confirmar la transacción
        $conn->commit();

        // Redirigir de vuelta al formulario con la orden guardada
        header("Location: form.php?id=" . $order_id . "&success=1");
        exit;

    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollBack();

        // Redirigir con mensaje de error
        $redirect_url = $order_id ? "form.php?id=" . $order_id : "form.php";
        header("Location: " . $redirect_url . "&error=1");
        exit;
    }
} else {
    // Si no es POST, redirigir al listado
    header("Location: index.php");
    exit;
}
