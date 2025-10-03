<?php
require '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_POST['customer_id'];
    $customer_name = $_POST['customer_name'];
    $contact_name = $_POST['contact_name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $postal_code = $_POST['postal_code'];
    $country = $_POST['country'];

    try {
        if (empty($customer_id)) {
            // INSERT: Crear nuevo producto
            $sql = "INSERT INTO Customers (CustomerName, ContactName, Address, City, PostalCode, Country) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$customer_name, $contact_name, $address, $city, $postal_code, $country]);
        } else {
            // UPDATE: Actualizar producto existente
            $sql = "UPDATE Customers SET CustomerName = ?, ContactName = ?, Address = ?, City = ?, PostalCode = ?,  Country = ? WHERE Customer_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$customer_name, $contact_name, $address, $city, $postal_code, $country, $customer_id]);
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