<?php
require '../includes/db_connection.php';
require '../includes/header.php';

$category = ['Category_ID' => '', 'CategoryName' => '', 'Description' => ''];
$page_title = 'Añadir Nueva Categoría';

// Si se proporciona un ID, estamos editando
if (isset($_GET['id'])) {
    $page_title = 'Editar Categoría';
    $stmt = $conn->prepare("SELECT * FROM Categories WHERE Category_ID = ?");
    $stmt->execute([$_GET['id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $category = $row;
    }
}
?>

<h2><?= htmlspecialchars($page_title) ?></h2>
<form action="save.php" method="POST">
    <input type="hidden" name="category_id" value="<?= htmlspecialchars($category['Category_ID']) ?>">

    <div class="form-group">
        <label for="category_name">Nombre de la Categoría:</label>
        <input type="text" id="category_name" name="category_name"
               value="<?= htmlspecialchars($category['CategoryName']) ?>" required>
    </div>

    <div class="form-group">
        <label for="description">Descripción:</label>
        <textarea id="description" name="description" rows="4"><?= htmlspecialchars($category['Description'] ?? '') ?></textarea>
    </div>

    <button type="submit" class="btn">Guardar Categoría</button>
    <a href="index.php" style="margin-left: 10px;">Cancelar</a>
</form>

<?php require '../includes/footer.php'; ?>
