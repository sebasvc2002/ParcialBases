<?php
require '../includes/db_connection.php';
require '../includes/header.php';

$product = ['Employee_ID' => '', 'LastName' => '', 'FirstName' => '', 'BirthDate' => '', 'Notes' => ''];
$page_title = 'Añadir Nuevo Empleado';

// Si se proporciona un ID, estamos editando
if (isset($_GET['id'])) {
    $page_title = 'Editar información de empleado';
    $stmt = $conn->prepare("SELECT * FROM Employees WHERE Employee_ID = ?");
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Obtener todas las categorías para el dropdown
$categoriesStmt = $conn->query("SELECT * FROM Employees ORDER BY LastName");
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <h2><?= $page_title ?></h2>
    <form action="save.php" method="POST">
        <input type="hidden" name="employee_id" value="<?= htmlspecialchars($employee['Employee_ID']) ?>">

        <div class="form-group">
            <label for="last_name">Apellido:</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($employee['LastName']) ?>" required>
        </div>

        <div class="form-group">
            <label for="first_name">Nombre:</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($employee['FirstName']) ?>" required>
        </div>

        <div class="form-group">
            <label for="birth_date">Fecha de Nacimiento:</label>
            <input type="date" id="birth_date" name="birth_date" value="<?= htmlspecialchars($employee['BirthDate'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="notes">Notas:</label>
            <textarea id="notes" name="notes" rows="4"><?= htmlspecialchars($employee['Notes'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn">Guardar Empleado</button>
        <a href="index.php" style="margin-left: 10px;">Cancelar</a>
    </form>

<?php require '../includes/footer.php'; ?>