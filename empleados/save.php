<?php
require '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $birth_date = $_POST['birth_date'];
    $notes = $_POST['notes'];

    try {
        if (empty($employee_id)) {
            // INSERT: Crear nuevo producto
            $sql = "INSERT INTO Employees (LastName, FirstName, BirthDate, Notes) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$last_name, $first_name, $birth_date, $notes]);
        } else {
            // UPDATE: Actualizar producto existente
            $sql = "UPDATE Employees SET LastName = ?, FirstName = ?, BirthDate = ?, Notes = ? WHERE Employee_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$last_name, $first_name, $birth_date, $notes, $employee_id]);
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