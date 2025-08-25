<?php
session_start();
include 'Configuracion.php'; 
include 'La-carta.php'; 

$cart = new Cart; 
if ($cart->total_items() <= 0) {
    header("Location: consultarProducto.php");
    exit();
}

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id'])) {
    die("Error: No has iniciado sesión. Por favor, vuelve a iniciar sesión.");
}

$idPersona = $_SESSION['id']; // Ahora es seguro usarlo

// Si se ha presionado el botón "Confirmar Pago"
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmarPago'])) {
    try {
        // Iniciar transacción
        $conexion->beginTransaction();
        
        // 1️⃣ Insertar en `recibo`
        $precioTotal = $cart->total(); 
        $stmt = $conexion->prepare("INSERT INTO recibo (IdPersona, PrecioTotal) VALUES (:IdPersona, :PrecioTotal)");
        $stmt->execute([
            ':IdPersona' => $idPersona,
            ':PrecioTotal' => $precioTotal
        ]);

        // Obtener el ID del recibo recién insertado
        $IdRecibo = $conexion->lastInsertId();

        // 2️⃣ Insertar en `detalleRecibo`
        $cartItems = $cart->contents();
        $stmt = $conexion->prepare("INSERT INTO detalleRecibo (IdRecibo, IdCalzado, Cantidad) VALUES (:IdRecibo, :IdCalzado, :Cantidad)");
        
        foreach ($cartItems as $item) {
            $stmt->execute([
                ':IdRecibo' => $IdRecibo,
                ':IdCalzado' => $item['IdCalzado'],
                ':Cantidad' => $item['qty']
            ]);
        }
        
        // Confirmar la transacción
        $conexion->commit();

        // 3️⃣ Vaciar el carrito después de la compra
        $cart->destroy();

        // 4️⃣ Redirigir a `OrdenExito.php`
        header("Location: OrdenExito.php?id=" . $IdRecibo);
        exit();

    } catch (PDOException $e) {
        $conexion->rollBack();
        echo "Error en el pago: " . $e->getMessage();
    }
}

// Obtener los datos del cliente
$query = $conexion->prepare("SELECT * FROM persona WHERE IdPersona = :idPersona");
$query->execute([':idPersona' => $idPersona]);
$custRow = $query->fetch(PDO::FETCH_ASSOC);
?>

<?php include('../../src/templates/header.php');
      include '../../src/templates/navegador.php'; ?>

<h1 class="text-center mt-5 mb-5 display-4">Vista previa de la orden</h1>

<div class="container mt-4">
    <div class="card border-0 shadow-none">
        <div class="card-body text-dark">
            <div class="row">
                <!-- Tabla de productos -->
                <div class="col-md-8">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Sub total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($cart->total_items() > 0) {
                                $cartItems = $cart->contents();
                                foreach ($cartItems as $item) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item["Descripcion"]); ?></td>
                                        <td><?php echo '$' . number_format($item["Precio"]) . ' COP'; ?></td>
                                        <td><?php echo $item["qty"]; ?></td>
                                        <td><?php echo '$' . number_format($item["subtotal"]) . ' COP'; ?></td>
                                    </tr>
                                <?php } 
                            } else { ?>
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <p class="text-muted">No hay artículos en tu carrito...</p>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <?php if ($cart->total_items() > 0) { ?>
                            <tfoot>
                                <tr>
                                    <td colspan="3"></td>
                                    <td class="text-end">
                                        <strong>Total: <?php echo '$' . number_format($cart->total()) . ' COP'; ?></strong>
                                    </td>
                                </tr>
                            </tfoot>
                        <?php } ?>
                    </table>
                </div>

                <!-- Detalles de envío -->
                <div class="col-md-4">
                    <h4>Detalles de envío</h4>
                    <?php if ($custRow) { ?>
                        <p><strong><?php echo htmlspecialchars($custRow['Nombre'] . ' ' . $custRow['Apellido']); ?></strong></p>
                        <p><?php echo htmlspecialchars($custRow['Correo']); ?></p>
                        <p><?php echo htmlspecialchars($custRow['Telefono']); ?></p>
                    <?php } else { ?>
                        <p class="text-muted">No se encontraron datos del cliente.</p>
                    <?php } ?>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="d-flex justify-content-between mt-3">
                <a href="VerCarta.php" class="btn btn-warning">
                    <i class="bi bi-arrow-left"></i> Continuar Comprando
                </a>
                
                <form action="Pagos.php" method="POST">
                    <button type="submit" name="confirmarPago" class="btn btn-success">
                        Confirmar Pago <i class="bi bi-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "../../src/templates/footer.php"; ?>
