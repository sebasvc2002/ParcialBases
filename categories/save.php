<?php
require '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id   = $_POST['category_id'];
    $category_name = trim($_POST['category_name'] ?? '');
    $description   = trim($_POST['description'] ?? '');

    // (opcional) validación mínima
    if ($category_name === '') {
        die('El nombre de la categoría es obligatorio');
    }

    try {
        if ($category_id === '' || $category_id === null) {
            // INSERT: crear nueva categoría
            $sql  = "INSERT INTO Categories (CategoryName, Description) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$category_name, $description !== '' ? $description : null]);
        } else {
            // UPDATE: actualizar categoría existente
            $sql  = "UPDATE Categories SET CategoryName = ?, Description = ? WHERE Category_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$category_name, $description !== '' ? $description : null, $category_id]);
        }
    } catch (PDOException $e) {
        // Manejo de errores (en una app real, podrías registrar el error o mostrar un mensaje más amigable)
        die("Error al guardar la categoría: " . $e->getMessage());
    }

    // Redirigir de vuelta al listado (ajusta la ruta si tu index es otro)
    header("Location: index.php");
    exit();
}