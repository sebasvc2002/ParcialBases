# Sistema de Gestión de Tienda - Miscelanea O.C.S.O

Un sistema web completo para la gestión de una tienda desarrollado en PHP con MySQL, que permite administrar productos, clientes, empleados, categorías y transacciones.

## 👥 Equipo de Desarrollo

- **Sebastián Velasco Cantú**
- **Ricardo Castillo Salvador**
- **Aldo Tomás Mundo Carrillo**

## 🚀 Características

### Módulos Principales
- **Gestión de Productos**: CRUD completo para productos con categorización
- **Gestión de Clientes**: Administración de información de clientes
- **Gestión de Empleados**: Control de personal de la tienda
- **Gestión de Categorías**: Organización de productos por categorías
- **Gestión de Transacciones**: Control de órdenes y detalles de venta

### Funcionalidades
- ✅ Operaciones CRUD (Crear, Leer, Actualizar, Eliminar) para todas las entidades
- ✅ Interfaz web responsive
- ✅ Base de datos relacional con integridad referencial
- ✅ Navegación intuitiva entre módulos
- ✅ Confirmaciones de eliminación para seguridad

## 🛠️ Tecnologías

- **Backend**: PHP 7.4+
- **Base de Datos**: MySQL
- **Frontend**: HTML5, CSS3
- **Servidor Web**: Apache/Nginx compatible

## 📁 Estructura del Proyecto

```
Parcial1Bases/
├── index.php                      # Página principal
├── Proyecto base de datos.sql      # Script de base de datos
├── README.md                       # Este archivo
├── css/
│   └── style.css                   # Estilos del sistema
├── includes/
│   ├── A_mayab.png                 # Logo de la aplicación
│   ├── db_connection.php           # Configuración de base de datos
│   ├── header.php                  # Header común
│   └── footer.php                  # Footer común
├── products/                       # Módulo de productos
│   ├── index.php                   # Lista de productos
│   ├── form.php                    # Formulario de productos
│   ├── save.php                    # Guardar producto
│   └── delete.php                  # Eliminar producto
├── customers/                      # Módulo de clientes
│   ├── index.php                   # Lista de clientes
│   ├── form.php                    # Formulario de clientes
│   ├── save.php                    # Guardar cliente
│   └── delete.php                  # Eliminar cliente
├── categories/                     # Módulo de categorías
│   ├── index.php                   # Lista de categorías
│   ├── form.php                    # Formulario de categorías
│   ├── save.php                    # Guardar categoría
│   └── delete.php                  # Eliminar categoría
├── empleados/                      # Módulo de empleados
│   ├── index.php                   # Lista de empleados
│   ├── form.php                    # Formulario de empleados
│   ├── save.php                    # Guardar empleado
│   └── delete.php                  # Eliminar empleado
└── ordenes/                        # Módulo de transacciones
    ├── index.php                   # Lista de órdenes
    ├── form.php                    # Formulario de órdenes
    ├── save.php                    # Guardar orden
    └── delete.php                  # Eliminar orden
```

## 🗄️ Base de Datos

El sistema utiliza una base de datos MySQL llamada `VentasDB` con las siguientes tablas:

### Tablas Principales
- **Customers**: Información de clientes
- **Employees**: Datos de empleados
- **Categories**: Categorías de productos
- **Products**: Catálogo de productos
- **Orders**: Órdenes de venta
- **OrderDetails**: Detalles de cada orden

### Relaciones
- Los productos están relacionados con categorías
- Las órdenes están vinculadas a clientes y empleados
- Los detalles de orden conectan productos con órdenes

## ⚙️ Instalación

### Prerrequisitos
- Servidor web (Apache/Nginx)
- PHP 7.4 o superior
- MySQL 5.7 o superior
- PDO PHP extension habilitada

### Pasos de Instalación

1. **Clonar o descargar el proyecto**
   ```bash
   git clone [URL_DEL_REPOSITORIO]
   ```

2. **Configurar la base de datos**
   - Crear una base de datos MySQL
   - Ejecutar el archivo `Proyecto base de datos.sql`

3. **Configurar la conexión**
   - Editar el archivo `includes/db_connection.php`
   - Actualizar las credenciales de la base de datos:
   ```php
   $servername = "tu_servidor";
   $username = "tu_usuario";
   $password = "tu_contraseña";
   $dbname = "VentasDB";
   ```

4. **Configurar el servidor web**
   - Colocar los archivos en el directorio web del servidor
   - Asegurar que PHP tenga permisos de escritura (si es necesario)

5. **Acceder al sistema**
   - Abrir navegador web
   - Navegar a la URL donde se instaló el proyecto

## 🎯 Uso del Sistema

### Navegación
El sistema cuenta con un menú principal que permite acceder a:
- **Inicio**: Página principal con información del equipo
- **Productos**: Gestión del catálogo de productos
- **Clientes**: Administración de clientes
- **Categorías**: Organización de categorías de productos
- **Empleados**: Control de personal
- **Transacciones**: Gestión de órdenes de venta

### Operaciones Disponibles
En cada módulo puedes:
- **Ver lista**: Visualizar todos los registros
- **Agregar nuevo**: Crear nuevos registros
- **Editar**: Modificar registros existentes
- **Eliminar**: Borrar registros (con confirmación)

## 🔒 Seguridad

- Uso de PDO para prevenir inyecciones SQL
- Validación y escape de datos de entrada con `htmlspecialchars()`
- Confirmaciones JavaScript para operaciones de eliminación
- Manejo de errores con try-catch

## 🤝 Contribución

Este proyecto fue desarrollado como parte de un parcial académico. Para contribuir:

1. Fork el proyecto
2. Crear una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crear un Pull Request

## 📝 Licencia

Este proyecto es de uso académico desarrollado para el curso de Bases de Datos.

## 📞 Contacto

Para dudas o consultas, contactar a cualquier miembro del equipo de desarrollo.

---
*Desarrollado con ❤️ por el equipo O.C.S.O*
