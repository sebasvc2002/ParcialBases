<?php
require '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
    $unit = $_POST['unit'];
    $price = $_POST['price'];

    try {
        if (empty($product_id)) {
            // INSERT: Crear nuevo producto
            $sql = "INSERT INTO Products (ProductName, Category_ID, Unit, Price) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$product_name, $category_id, $unit, $price]);
        } else {
            // UPDATE: Actualizar producto existente
            $sql = "UPDATE Products SET ProductName = ?, Category_ID = ?, Unit = ?, Price = ? WHERE Product_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$product_name, $category_id, $unit, $price, $product_id]);
        }
    } catch (PDOException $e) {
        // Manejo de errores (en una app real, podrías registrar el error o mostrar un mensaje más amigable)
        die("Error al guardar el producto: " . $e->getMessage());
    }

    // Redirigir de vuelta a la lista de productos
    header("Location: index.php");
    exit();
}
?>