<?php
require '../includes/db_connection.php';
require '../includes/header.php';

$stmt = $conn->query("
    SELECT c.Customer_ID, c.CustomerName, c.ContactName, c.Address, c.City, c.PostalCode, c.Country
    FROM Customers c
    ORDER BY c.Customer_ID
");
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <h2>Gestión de Clientes</h2>
    <a href="form.php" class="btn">Añadir Nuevo Cliente</a>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Nombre Completo</th>
            <th>Nombre de Contacto</th>
            <th>Dirección</th>
            <th>Código Postal</th>
            <th>Ciudad</th>
            <th>País</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($customers as $customers): ?>
            <tr>
                <td><?= htmlspecialchars($customers['Customer_ID'])?></td>
                <td><?= htmlspecialchars($customers['CustomerName']) ?></td>
                <td><?= htmlspecialchars($customers['ContactName']) ?></td>
                <td><?= htmlspecialchars($customers['Address']) ?></td>
                <td><?= htmlspecialchars($customers['PostalCode']) ?></td>
                <td><?= htmlspecialchars($customers['City']) ?></td>
                <td><?= htmlspecialchars($customers['Country']) ?></td>
                <td>
                    <a href="form.php?id=<?= $customers['Customer_ID'] ?>" class="btn btn-edit">Editar</a>
                    <a href="delete.php?id=<?= $customers['Customer_ID'] ?>" class="btn btn-delete" onclick="return confirm('¿Estás seguro de que deseas eliminar este cliente?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php require '../includes/footer.php'; ?>