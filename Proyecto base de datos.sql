

DROP DATABASE IF EXISTS VentasDB;
CREATE DATABASE VentasDB;
USE VentasDB;

-- =======================
-- TABLA: Customers
-- =======================
CREATE TABLE Customers (
    Customer_ID INT NOT NULL AUTO_INCREMENT,
    CustomerName VARCHAR(100) NOT NULL,
    ContactName VARCHAR(100),
    Address VARCHAR(150),
    City VARCHAR(100),
    PostalCode VARCHAR(20),
    Country VARCHAR(50),
    PRIMARY KEY (Customer_ID)
);

-- =======================
-- TABLA: Employees
-- =======================
CREATE TABLE Employees (
    Employee_ID INT NOT NULL AUTO_INCREMENT,
    LastName VARCHAR(100) NOT NULL,
    FirstName VARCHAR(100) NOT NULL,
    BirthDate DATE,
    Photo BLOB,
    Notes TEXT,
    PRIMARY KEY (Employee_ID)
);

-- =======================
-- TABLA: Categories
-- =======================
CREATE TABLE Categories (
    Category_ID INT NOT NULL AUTO_INCREMENT,
    CategoryName VARCHAR(100) NOT NULL,
    Description TEXT,
    PRIMARY KEY (Category_ID)
);

-- =======================
-- TABLA: Products
-- =======================
CREATE TABLE Products (
    Product_ID INT NOT NULL AUTO_INCREMENT,
    ProductName VARCHAR(100) NOT NULL,
    Category_ID INT,
    Unit VARCHAR(50),
    Price DECIMAL(10,2),
    PRIMARY KEY (Product_ID),
    CONSTRAINT fk_Products_Categories
        FOREIGN KEY (Category_ID) REFERENCES Categories(Category_ID)
        ON UPDATE CASCADE ON DELETE SET NULL
);

-- =======================
-- TABLA: Orders
-- =======================
CREATE TABLE Orders (
    Order_ID INT NOT NULL AUTO_INCREMENT,
    Customer_ID INT,
    Employee_ID INT,
    OrderDate DATE,
    PRIMARY KEY (Order_ID),
    CONSTRAINT fk_Orders_Customers
        FOREIGN KEY (Customer_ID) REFERENCES Customers(Customer_ID)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_Orders_Employees
        FOREIGN KEY (Employee_ID) REFERENCES Employees(Employee_ID)
        ON UPDATE CASCADE ON DELETE SET NULL
);

-- =======================
-- TABLA: OrderDetails
-- =======================
CREATE TABLE OrderDetails (
    OrderDetail_ID INT NOT NULL AUTO_INCREMENT,
    Order_ID INT,
    Product_ID INT,
    Quantity INT NOT NULL,
    PRIMARY KEY (OrderDetail_ID),
    CONSTRAINT fk_OrderDetails_Orders
        FOREIGN KEY (Order_ID) REFERENCES Orders(Order_ID)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_OrderDetails_Products
        FOREIGN KEY (Product_ID) REFERENCES Products(Product_ID)
        ON UPDATE CASCADE ON DELETE CASCADE
);

-- ========================================================
-- DATOS DE PRUEBA
-- ========================================================

INSERT INTO Categories (CategoryName, Description) VALUES
('Bebidas', 'Refrescos, jugos, tés y cafés'),
('Snacks', 'Galletas, papas fritas y botanas'),
('Lácteos', 'Leche, quesos y yogures');

INSERT INTO Products (ProductName, Category_ID, Unit, Price) VALUES
('Coca-Cola 600ml', 1, 'Botella', 15.00),
('Pepsi 600ml', 1, 'Botella', 14.50),
('Doritos 150g', 2, 'Bolsa', 20.00),
('Galletas Oreo', 2, 'Paquete', 18.00),
('Leche Lala 1L', 3, 'Cartón', 22.50),
('Yoghurt Danone 125g', 3, 'Vaso', 12.00);

INSERT INTO Customers (CustomerName, ContactName, Address, City, PostalCode, Country) VALUES
('Supermercado A', 'Luis Pérez', 'Av. Central 123', 'Ciudad de México', '01000', 'México'),
('Tienda B', 'Ana Gómez', 'Calle Reforma 456', 'Monterrey', '64000', 'México'),
('MiniMarket C', 'José Ramírez', 'Calle Hidalgo 789', 'Guadalajara', '44100', 'México');

INSERT INTO Employees (LastName, FirstName, BirthDate, Photo, Notes) VALUES
('Hernández', 'María', '1990-05-10', NULL, 'Encargada de zona norte'),
('López', 'Carlos', '1985-09-22', NULL, 'Encargado de ventas mayoristas'),
('Martínez', 'Sofía', '1992-12-03', NULL, 'Atención a clientes especiales');

INSERT INTO Orders (Customer_ID, Employee_ID, OrderDate) VALUES
(1, 1, '2025-09-01'),
(2, 2, '2025-09-05'),
(3, 3, '2025-09-10');

INSERT INTO OrderDetails (Order_ID, Product_ID, Quantity) VALUES
(1, 1, 10),
(1, 3, 5),
(2, 2, 20),
(2, 5, 15),
(3, 4, 8),
(3, 6, 12);

-- ========================================================
-- CONSULTAS DE PRUEBA
-- ========================================================

-- Ver pedidos con cliente y empleado
SELECT o.Order_ID, c.CustomerName, e.FirstName, e.LastName, o.OrderDate
FROM Orders o
JOIN Customers c ON o.Customer_ID = c.Customer_ID
JOIN Employees e ON o.Employee_ID = e.Employee_ID;

-- Ver detalles de un pedido con productos y total
SELECT od.Order_ID, p.ProductName, od.Quantity, p.Price, (od.Quantity * p.Price) AS Total
FROM OrderDetails od
JOIN Products p ON od.Product_ID = p.Product_ID
WHERE od.Order_ID = 1;
