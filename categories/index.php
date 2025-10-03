<?php
require '../includes/db_connection.php';
require '../includes/header.php';

$stmt = $conn->query("
    SELECT Category_ID, CategoryName, Description
    FROM Categories
    ORDER BY CategoryName
");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Gestión de Categorías</h2>
<a href="form.php" class="btn">Añadir Nueva Categoría</a>

<table>
    <thead>
    <tr>
        <th>ID</th>
		<th>Categoria</th>
        <th>Descripción</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($categories as $cat): ?>
        <tr>
			<td><?= htmlspecialchars($cat['Category_ID']) ?></td>
            <td><?= htmlspecialchars($cat['CategoryName']) ?></td>
            <td><?= htmlspecialchars($cat['Description'] ?? '') ?></td>
            <td>
                <a href="form.php?id=<?= $cat['Category_ID'] ?>" class="btn btn-edit">Editar</a>
                <a href="delete.php?id=<?= $cat['Category_ID'] ?>" class="btn btn-delete"
                   onclick="return confirm('¿Estás seguro de que deseas eliminar esta categoría?');">
                   Eliminar
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php require '../includes/footer.php'; ?>
