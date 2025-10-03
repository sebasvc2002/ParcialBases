<?php
require '../includes/db_connection.php';
require '../includes/header.php';

$customer= ['Customer_ID' => '', 'CustomerName' => '', 'ContactName' => '', 'Address' => '', 'City' => '', 'PostalCode' => '', 'Country'];
$page_title = 'Añadir Nuevo Cliente';

// Si se proporciona un ID, estamos editando
if (isset($_GET['id'])) {
    $page_title = 'Editar Cliente';
    $stmt = $conn->prepare("SELECT * FROM Customers WHERE Customer_ID = ?");
    $stmt->execute([$_GET['id']]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Obtener todas las categorías para el dropdown
$categoriesStmt = $conn->query("SELECT * FROM Customers ORDER BY Customer_ID");
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <h2><?= $page_title ?></h2>
    <form action="save.php" method="POST">
        <input type="hidden" name="customer_id" value="<?= htmlspecialchars($customer['Customer_ID']) ?>">

        <div class="form-group">
            <label for="customer_name">Nombre Completo:</label>
            <input type="text" id="customer_name" name="customer_name" value="<?= htmlspecialchars($customer['CustomerName']) ?>" required>
        </div>

        <div class="form-group">
            <label for="contact_name">Nombre del Contacto:</label>
            <input type="text" id="contact_name" name="contact_name" value="<?= htmlspecialchars($customer['ContactName']) ?>" required>
        </div>

        <div class="form-group">
            <label for="address">Dirección:</label>
            <input type="text" id="address" name="address" value="<?= htmlspecialchars($customer['Address']) ?>">
        </div>

        <div class="form-group">
            <label for="city">Ciudad:</label>
            <input type="text" id="city" name="city" value="<?= htmlspecialchars($customer['City']) ?>">
        </div>

        <div class="form-group">
            <label for="postal_code">Código Postal:</label>
            <input type="text" id="postal_code" name="postal_code" value="<?= htmlspecialchars($customer['PostalCode']) ?>">
        </div>

        <div class="form-group">
            <label for="country">País:</label>
            <input type="text" id="country" name="country" value="<?= htmlspecialchars($customer['Country']) ?>">
        </div>

        <button type="submit" class="btn">Guardar Cliente</button>
        <a href="index.php" style="margin-left: 10px;">Cancelar</a>
    </form>

<?php require '../includes/footer.php'; ?>