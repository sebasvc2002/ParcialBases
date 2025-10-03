<?php
require '../includes/db_connection.php';
require '../includes/header.php';

// Obtener todas las órdenes con información de cliente, empleado y total
$stmt = $conn->query("
    SELECT o.Order_ID, c.ContactName as CustomerName, 
           CONCAT(e.FirstName, ' ', e.LastName) as EmployeeName, 
           o.OrderDate,
           COALESCE(SUM(od.Quantity * p.Price), 0) as Total
    FROM Orders o
    LEFT JOIN Customers c ON o.Customer_ID = c.Customer_ID
    LEFT JOIN Employees e ON o.Employee_ID = e.Employee_ID
    LEFT JOIN OrderDetails od ON o.Order_ID = od.Order_ID
    LEFT JOIN Products p ON od.Product_ID = p.Product_ID
    GROUP BY o.Order_ID, c.ContactName, e.FirstName, e.LastName, o.OrderDate
    ORDER BY o.Order_ID DESC
");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar mensajes de éxito o error
$message = '';
$messageType = '';

if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'deleted':
            $message = 'Orden eliminada exitosamente.';
            $messageType = 'success';
            break;
    }
}

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'order_not_found':
            $message = 'La orden que intentas eliminar no existe.';
            $messageType = 'error';
            break;
        case 'delete_failed':
            $message = 'Error al eliminar la orden. Inténtalo de nuevo.';
            $messageType = 'error';
            break;
        case 'no_id':
            $message = 'ID de orden no válido.';
            $messageType = 'error';
            break;
    }
}
?>

<h2>Gestión de Órdenes</h2>

<?php if ($message): ?>
    <div class="message <?= $messageType ?>">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<div style="margin-bottom: 20px;">
    <a href="form.php" class="btn btn-primary">Nueva Orden</a>
</div>

<?php if (empty($orders)): ?>
    <div style="text-align: center; padding: 40px; color: #888;">
        <h3>No hay órdenes registradas</h3>
        <p>Comienza creando tu primera orden haciendo clic en "Nueva Orden"</p>
    </div>
<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th>ID Orden</th>
                <th>Cliente</th>
                <th>Empleado</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['Order_ID']) ?></td>
                    <td><?= htmlspecialchars($order['CustomerName'] ?? 'Sin cliente') ?></td>
                    <td><?= htmlspecialchars($order['EmployeeName'] ?? 'Sin empleado') ?></td>
                    <td><?= htmlspecialchars($order['OrderDate']) ?></td>
                    <td>$<?= number_format($order['Total'], 2) ?></td>
                    <td>
                        <a href="form.php?id=<?= $order['Order_ID'] ?>" class="btn btn-small">Editar</a>
                        <a href="#" onclick="confirmDelete(<?= $order['Order_ID'] ?>)" class="btn btn-small btn-danger">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<script>
function confirmDelete(orderId) {
    if (confirm('¿Estás seguro de que deseas eliminar esta orden? Esta acción no se puede deshacer.')) {
        window.location.href = 'delete.php?id=' + orderId;
    }
}

// Auto-ocultar mensajes después de 5 segundos
document.addEventListener('DOMContentLoaded', function() {
    const message = document.querySelector('.message');
    if (message) {
        setTimeout(function() {
            message.style.transition = 'opacity 0.5s';
            message.style.opacity = '0';
            setTimeout(function() {
                message.remove();
            }, 500);
        }, 5000);
    }
});
</script>

<style>
.message {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
    font-weight: bold;
}

.message.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.message.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.btn {
    display: inline-block;
    padding: 8px 16px;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
    border: none;
    margin-right: 5px;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-small {
    padding: 4px 8px;
    font-size: 12px;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.btn-danger:hover {
    background-color: #c82333;
}

table {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

table th {
    background-color: #f8f9fa;
    font-weight: bold;
    text-align: left;
}

table td, table th {
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

table tr:hover {
    background-color: #f5f5f5;
}
</style>

<?php require '../includes/footer.php'; ?>
