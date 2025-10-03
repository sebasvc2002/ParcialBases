<?php
require '../includes/db_connection.php';
require '../includes/header.php';

$product = ['Product_ID' => '', 'ProductName' => '', 'Category_ID' => '', 'Unit' => '', 'Price' => ''];
$page_title = 'Añadir Nuevo Producto';

// Si se proporciona un ID, estamos editando
if (isset($_GET['id'])) {
    $page_title = 'Editar Producto';
    $stmt = $conn->prepare("SELECT * FROM Products WHERE Product_ID = ?");
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Obtener todas las categorías para el dropdown
$categoriesStmt = $conn->query("SELECT * FROM Categories ORDER BY CategoryName");
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <h2><?= $page_title ?></h2>
    <form action="save.php" method="POST">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['Product_ID']) ?>">

        <div class="form-group">
            <label for="product_name">Nombre del Producto:</label>
            <input type="text" id="product_name" name="product_name" value="<?= htmlspecialchars($product['ProductName']) ?>" required>
        </div>

        <div class="form-group">
            <label for="category_id">Categoría:</label>
            <select id="category_id" name="category_id">
                <option value="">Selecciona una categoría</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['Category_ID'] ?>" <?= ($product['Category_ID'] == $category['Category_ID']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['CategoryName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="unit">Unidad:</label>
            <input type="text" id="unit" name="unit" value="<?= htmlspecialchars($product['Unit']) ?>">
        </div>

        <div class="form-group">
            <label for="price">Precio:</label>
            <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($product['Price']) ?>" required>
        </div>

        <button type="submit" class="btn">Guardar Producto</button>
        <a href="index.php" style="margin-left: 10px;">Cancelar</a>
    </form>

<?php require '../includes/footer.php'; ?>