<?php
// Incluir el archivo de conexión a la base de datos
include 'db.php';

// Obtener los artículos de la base de datos
$sql = "SELECT * FROM articulos";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            flex: 1;
        }

        .row-cols-1 .col,
        .row-cols-sm-2 .col,
        .row-cols-md-3 .col {
            display: flex;
            align-items: stretch;
            justify-content: center;
        }

        .card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            max-width: 300px; /* Asegura que las tarjetas tengan el mismo ancho */
            margin: auto; /* Centra las tarjetas horizontalmente */
        }

        .card img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
        }

        footer {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            padding: 10px 0;
        }

        .cart-icon {
            position: relative;
        }

        .cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }

        .modal-footer .btn {
            margin-right: 10px; /* Añade margen entre los botones */
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php">Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="admin.php">Administrar Artículos</a>
                </li>
            </ul>
        </div>
        <!-- Ícono del carrito al lado del menú hamburguesa -->
        <button class="btn btn-outline-secondary cart-icon ms-3" data-bs-toggle="modal" data-bs-target="#modalCarrito">
            <i class="bi bi-cart-fill"></i>
            <span class="cart-count" id="cartCount">0</span>
        </button>
    </div>
</nav>

<!-- Contenido Principal -->
<div class="container mt-4">
    <h1 class="mb-4">Pizzería La Muzza Loca</h1>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col d-flex">
                    <div class="card">
                        <img src="img/<?php echo $row['imagen']; ?>" class="card-img-top" alt="<?php echo $row['nombre']; ?>">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title"><?php echo $row['nombre']; ?></h5>
                            <p class="card-text">Precio: $<?php echo $row['precio']; ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <button class="btn btn-danger quantity-btn" onclick="updateQuantity(-1, <?php echo $row['id']; ?>)">-</button>
                                <span id="quantity-<?php echo $row['id']; ?>" class="mx-3">0</span>
                                <button class="btn btn-success quantity-btn" onclick="updateQuantity(1, <?php echo $row['id']; ?>)">+</button>
                            </div>
                            <button class="btn btn-primary mt-3" onclick="addToCart(<?php echo $row['id']; ?>, '<?php echo $row['nombre']; ?>', <?php echo $row['precio']; ?>)">Agregar al carrito</button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay artículos disponibles en la tienda.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de Carrito -->
<div class="modal fade" id="modalCarrito" tabindex="-1" aria-labelledby="modalCarritoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCarritoLabel">Carrito de Compras</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p>Total: $<span id="total">0</span></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" onclick="emptyCart()">Vaciar Carrito</button>
                <button class="btn btn-success" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalCompra">Continuar con la Compra</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Finalización de Compra -->
<div class="modal fade" id="modalCompra" tabindex="-1" aria-labelledby="modalCompraLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCompraLabel">Detalles de la Compra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="whatsappForm">
                    <!-- Campos del formulario -->
                    <div class="mb-3">
                        <label for="nombreCliente" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombreCliente" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellidoCliente" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellidoCliente" required>
                    </div>
                    <div class="mb-3">
                        <label for="calle" class="form-label">Calle</label>
                        <input type="text" class="form-control" id="calle" required>
                    </div>
                    <div class="mb-3">
                        <label for="numero" class="form-label">Número</label>
                        <input type="text" class="form-control" id="numero" required>
                    </div>
                    <div class="mb-3">
                        <p id="detalleCompra">Total de la compra: $0</p>
                        <p>Uso de la app: $1600</p>
                        <p>Costo de delivery: $2500</p>
                        <p><strong>Total a pagar: $<span id="totalPagar">0</span></strong></p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" onclick="sendWhatsApp()">Enviar WhatsApp</button>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-light text-center text-lg-start border-top shadow-sm mt-5">
    <div class="container d-flex justify-content-between align-items-center py-2">
        <a href="https://www.facebook.com" target="_blank" class="text-dark text-decoration-none"><i class="bi bi-facebook"></i> Facebook</a>
        <span class="text-muted">Derechos reservados oscarsoft &copy; 2024</span>
        <a href="https://www.instagram.com" target="_blank" class="text-dark text-decoration-none"><i class="bi bi-instagram"></i> Instagram</a>
    </div>
</footer>

<!-- Scripts de Bootstrap y Funciones de Carrito -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
<script>
    let cart = {}; // Carrito vacío

    function updateQuantity(amount, id) {
        const quantityElement = document.getElementById('quantity-' + id);
        let quantity = parseInt(quantityElement.textContent) + amount;
        if (quantity < 0) quantity = 0;
        quantityElement.textContent = quantity;
    }

    function addToCart(id, name, price) {
        const quantity = parseInt(document.getElementById('quantity-' + id).textContent);
        if (quantity > 0) {
            cart[id] = {name: name, price: price, quantity: quantity};
            updateCartTotal();
        }
    }

    function updateCartTotal() {
        let total = 0;
        let itemCount = 0; // Contador de cantidad de productos
        for (const item in cart) {
            total += cart[item].price * cart[item].quantity;
            itemCount += cart[item].quantity; // Sumar cantidad de cada producto
        }
        document.getElementById('total').textContent = total;
        document.getElementById('cartCount').textContent = itemCount; // Actualizar el contador del carrito
        document.getElementById('detalleCompra').textContent = `Total de la compra: $${total}`;
        document.getElementById('totalPagar').textContent = total + 1600 + 2500; // Añadiendo costos fijos
    }

    function emptyCart() {
        cart = {}; // Vaciar el carrito
        updateCartTotal(); // Actualizar el total del carrito
    }

    function sendWhatsApp() {
        const nombre = document.getElementById('nombreCliente').value;
        const apellido = document.getElementById('apellidoCliente').value;
        const calle = document.getElementById('calle').value;
        const numero = document.getElementById('numero').value;
        let message = `Detalles del pedido:\nNombre: ${nombre} ${apellido}\nDirección: ${calle} ${numero}\n`;
        for (const item in cart) {
            message += `${cart[item].name} - Cantidad: ${cart[item].quantity}, Precio Unitario: ${cart[item].price}\n`;
        }
        message += `Total: $${document.getElementById('total').textContent}\nUso de la app: $1600\nCosto de delivery: $2500\nTotal a pagar: $${document.getElementById('totalPagar').textContent}`;
        window.open(`https://wa.me/?text=${encodeURIComponent(message)}`);
    }
</script>
</body>
</html>
