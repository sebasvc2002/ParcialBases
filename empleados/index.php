<?php
require '../includes/db_connection.php';
require '../includes/header.php';

$stmt = $conn->query("
    SELECT Employee_ID, FirstName, LastName, BirthDate, Notes
    FROM Employees
    ORDER BY LastName
");
$employee = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <h2>Gestión de Empleados</h2>
    <a href="form.php" class="btn">Añadir Nuevo Empleado</a>

    <table>
        <thead>
        <tr>
            <th>ID de Empleado</th>
            <th>Apellido</th>
            <th>Nombre</th>
            <th>Fecha de Nacimiento</th>
            <th>Notas</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($employee as $emp): ?>
            <tr>
                <td><?= htmlspecialchars($emp['Employee_ID']) ?></td>
				<td><?= htmlspecialchars($emp['LastName']) ?></td>
                <td><?= htmlspecialchars($emp['FirstName']) ?></td>
                <td><?= htmlspecialchars($emp['BirthDate']) ?></td>
                <td><?= htmlspecialchars($emp['Notes']) ?></td>
                <td>
                    <a href="form.php?id=<?= $emp['Employee_ID'] ?>" class="btn btn-edit">Editar</a>
                    <a href="delete.php?id=<?= $emp['Employee_ID'] ?>" class="btn btn-delete" onclick="return confirm('¿Estás seguro de que deseas eliminar este empleado?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php require '../includes/footer.php'; ?>