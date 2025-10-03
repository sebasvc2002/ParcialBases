<?php
require '../includes/db_connection.php';
require '../includes/header.php';

$order = ['Order_ID' => '', 'Customer_ID' => '', 'Employee_ID' => '', 'OrderDate' => ''];
$order_details = [];
$page_title = 'Crear Nueva Orden';

// Si estamos editando una orden existente
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $page_title = 'Modificar Orden';

    // Obtener información de la orden
    $stmt = $conn->prepare("SELECT * FROM Orders WHERE Order_ID = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    // Obtener detalles de la orden
    $detailsStmt = $conn->prepare("
        SELECT od.OrderDetail_ID, od.Product_ID, p.ProductName, od.Quantity, p.Price, 
               (od.Quantity * p.Price) AS Subtotal
        FROM OrderDetails od
        JOIN Products p ON od.Product_ID = p.Product_ID
        WHERE od.Order_ID = ?
    ");
    $detailsStmt->execute([$order_id]);
    $order_details = $detailsStmt->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener clientes para el dropdown
$customersStmt = $conn->query("SELECT Customer_ID, ContactName FROM Customers ORDER BY ContactName");
$customers = $customersStmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener empleados para el dropdown
$employeesStmt = $conn->query("SELECT Employee_ID, CONCAT(FirstName, ' ', LastName) AS FullName FROM Employees ORDER BY FirstName");
$employees = $employeesStmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener productos para el dropdown de agregar
$productsStmt = $conn->query("SELECT Product_ID, ProductName, Price FROM Products ORDER BY ProductName");
$products = $productsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2><?= $page_title ?></h2>

<form action="save.php" method="POST" id="orderForm">
    <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['Order_ID']) ?>">

    <!-- Información básica de la orden -->
    <div class="form-group">
        <label for="customer_id">Cliente:</label>
        <select id="customer_id" name="customer_id" required>
            <option value="">Selecciona un cliente</option>
            <?php foreach ($customers as $customer): ?>
                <option value="<?= $customer['Customer_ID'] ?>" <?= ($customer['Customer_ID'] == $order['Customer_ID']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($customer['ContactName']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="employee_id">Empleado:</label>
        <select id="employee_id" name="employee_id" required>
            <option value="">Selecciona un empleado</option>
            <?php foreach ($employees as $employee): ?>
                <option value="<?= $employee['Employee_ID'] ?>" <?= ($employee['Employee_ID'] == $order['Employee_ID']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($employee['FullName']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="order_date">Fecha de Orden:</label>
        <input type="date" id="order_date" name="order_date" value="<?= htmlspecialchars($order['OrderDate'] ?: date('Y-m-d')) ?>" required>
    </div>

    <!-- Productos en la orden -->
    <h3>Productos en la Orden</h3>

    <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; margin-bottom: 20px;" id="productsTable">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="productsBody">
            <?php if (empty($order_details)): ?>
                <tr id="noProductsRow">
                    <td colspan="5" style="text-align: center; color: #888;">
                        No hay productos en la orden. Usa el formulario de abajo para agregar productos.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($order_details as $detail): ?>
                    <tr class="product-row" data-detail-id="<?= $detail['OrderDetail_ID'] ?>">
                        <td>
                            <select name="products[<?= $detail['OrderDetail_ID'] ?>][product_id]" class="product-select" required>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?= $product['Product_ID'] ?>"
                                            data-price="<?= $product['Price'] ?>"
                                            <?= ($product['Product_ID'] == $detail['Product_ID']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($product['ProductName']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="price-cell">$<?= number_format($detail['Price'], 2) ?></td>
                        <td>
                            <input type="number" name="products[<?= $detail['OrderDetail_ID'] ?>][quantity]"
                                   value="<?= $detail['Quantity'] ?>" min="1" class="quantity-input" required>
                        </td>
                        <td class="subtotal-cell">$<?= number_format($detail['Subtotal'], 2) ?></td>
                        <td>
                            <button type="button" class="remove-product" onclick="removeProduct(this)">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Agregar nuevo producto -->
    <div style="background: #f5f5f5; padding: 15px; margin-bottom: 20px;">
        <h4>Agregar Producto</h4>
        <div style="display: flex; gap: 10px; align-items: end;">
            <div>
                <label for="new_product">Producto:</label>
                <select id="new_product">
                    <option value="">Selecciona un producto</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?= $product['Product_ID'] ?>" data-price="<?= $product['Price'] ?>">
                            <?= htmlspecialchars($product['ProductName']) ?> - $<?= number_format($product['Price'], 2) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="new_quantity">Cantidad:</label>
                <input type="number" id="new_quantity" min="1" value="1">
            </div>
            <button type="button" onclick="addProduct()">Agregar Producto</button>
        </div>
    </div>

    <!-- Total de la orden -->
    <div style="text-align: right; margin-bottom: 20px;">
        <h3>Total de la Orden: $<span id="orderTotal">0.00</span></h3>
    </div>

    <button type="submit" class="btn">Guardar Orden</button>
    <a href="index.php" style="margin-left: 10px;">Cancelar</a>
</form>

<script>
let productCounter = <?= count($order_details) > 0 ? max(array_keys($order_details)) + 1 : 0 ?>;

function addProduct() {
    const productSelect = document.getElementById('new_product');
    const quantityInput = document.getElementById('new_quantity');

    if (!productSelect.value || !quantityInput.value) {
        alert('Por favor selecciona un producto y especifica la cantidad');
        return;
    }

    const productId = productSelect.value;
    const productName = productSelect.options[productSelect.selectedIndex].text.split(' - $')[0];
    const price = parseFloat(productSelect.options[productSelect.selectedIndex].dataset.price);
    const quantity = parseInt(quantityInput.value);
    const subtotal = price * quantity;

    // Remover fila de "no productos" si existe
    const noProductsRow = document.getElementById('noProductsRow');
    if (noProductsRow) {
        noProductsRow.remove();
    }

    // Crear nueva fila
    const tbody = document.getElementById('productsBody');
    const row = document.createElement('tr');
    row.className = 'product-row';
    row.dataset.detailId = 'new_' + productCounter;

    row.innerHTML = `
        <td>
            <select name="products[new_${productCounter}][product_id]" class="product-select" required>
                <?php foreach ($products as $product): ?>
                    <option value="<?= $product['Product_ID'] ?>"
                            data-price="<?= $product['Price'] ?>"
                            ${productId == '<?= $product['Product_ID'] ?>' ? 'selected' : ''}>
                        <?= htmlspecialchars($product['ProductName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
        <td class="price-cell">$${price.toFixed(2)}</td>
        <td>
            <input type="number" name="products[new_${productCounter}][quantity]"
                   value="${quantity}" min="1" class="quantity-input" required>
        </td>
        <td class="subtotal-cell">$${subtotal.toFixed(2)}</td>
        <td>
            <button type="button" class="remove-product" onclick="removeProduct(this)">Eliminar</button>
        </td>
    `;

    tbody.appendChild(row);

    // Resetear formulario de agregar
    productSelect.value = '';
    quantityInput.value = 1;

    // Actualizar eventos
    addEventListeners();
    updateTotal();
    productCounter++;
}

function removeProduct(button) {
    const row = button.closest('tr');
    row.remove();

    // Si no quedan productos, mostrar mensaje
    const tbody = document.getElementById('productsBody');
    if (tbody.children.length === 0) {
        tbody.innerHTML = `
            <tr id="noProductsRow">
                <td colspan="5" style="text-align: center; color: #888;">
                    No hay productos en la orden. Usa el formulario de abajo para agregar productos.
                </td>
            </tr>
        `;
    }

    updateTotal();
}

function updateTotal() {
    const subtotalCells = document.querySelectorAll('.subtotal-cell');
    let total = 0;

    subtotalCells.forEach(cell => {
        const value = parseFloat(cell.textContent.replace('$', '').replace(',', ''));
        if (!isNaN(value)) {
            total += value;
        }
    });

    document.getElementById('orderTotal').textContent = total.toFixed(2);
}

function addEventListeners() {
    // Evento para cambio de producto
    document.querySelectorAll('.product-select').forEach(select => {
        select.addEventListener('change', function() {
            const row = this.closest('tr');
            const priceCell = row.querySelector('.price-cell');
            const quantityInput = row.querySelector('.quantity-input');
            const subtotalCell = row.querySelector('.subtotal-cell');

            const selectedOption = this.options[this.selectedIndex];
            const price = parseFloat(selectedOption.dataset.price);
            const quantity = parseInt(quantityInput.value);

            priceCell.textContent = '$' + price.toFixed(2);
            subtotalCell.textContent = '$' + (price * quantity).toFixed(2);

            updateTotal();
        });
    });

    // Evento para cambio de cantidad
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('input', function() {
            const row = this.closest('tr');
            const priceCell = row.querySelector('.price-cell');
            const subtotalCell = row.querySelector('.subtotal-cell');

            const price = parseFloat(priceCell.textContent.replace('$', ''));
            const quantity = parseInt(this.value) || 0;

            subtotalCell.textContent = '$' + (price * quantity).toFixed(2);

            updateTotal();
        });
    });
}

// Inicializar eventos al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    addEventListeners();
    updateTotal();
});
</script>

<style>
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input, .form-group select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

table {
    border-collapse: collapse;
}

table th, table td {
    text-align: left;
    vertical-align: middle;
}

.quantity-input {
    width: 80px;
    text-align: center;
}

.remove-product {
    background: #dc3545;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 3px;
    cursor: pointer;
}

.remove-product:hover {
    background: #c82333;
}

.btn {
    background: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}

.btn:hover {
    background: #0056b3;
}
</style>

<?php require '../includes/footer.php'; ?>
