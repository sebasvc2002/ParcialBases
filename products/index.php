<?php
require '../includes/db_connection.php';
require '../includes/header.php';

$stmt = $conn->query("
    SELECT p.Product_ID, p.ProductName, c.CategoryName, p.Unit, p.Price
    FROM Products p
    LEFT JOIN Categories c ON p.Category_ID = c.Category_ID
    ORDER BY p.ProductName
");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <h2>Gestión de Productos</h2>
    <a href="form.php" class="btn">Añadir Nuevo Producto</a>

    <table>
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Unidad</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['ProductName']) ?></td>
                <td><?= htmlspecialchars($product['CategoryName'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($product['Unit']) ?></td>
                <td>$<?= number_format($product['Price'], 2) ?></td>
                <td>
                    <a href="form.php?id=<?= $product['Product_ID'] ?>" class="btn btn-edit">Editar</a>
                    <a href="delete.php?id=<?= $product['Product_ID'] ?>" class="btn btn-delete" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php require '../includes/footer.php'; ?>