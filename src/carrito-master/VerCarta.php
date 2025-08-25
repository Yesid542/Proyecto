<?php
// Inicializa la clase del carrito
include 'La-carta.php';
$cart = new Cart;
?>

<?php include('../../src/templates/header.php');
      include '../../src/templates/navegador.php'; ?>

<head>
    <script>
        function updateCartItem(obj, id) {
            $.get("cartAction.php", {
                action: "updateCartItem",
                id: id,
                qty: obj.value
            }, function(data) {
                if (data == 'ok') {
                    location.reload();
                } else {
                    alert('Cart update failed, please try again.');
                }
            });
        }
    </script>
</head>

<h1 class="text-center mt-5 mb-5 display-4">Carrito de compras</h1>

<div class="container">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th class="text-center">Cantidad</th>
                    <th>Sub total</th>
                    <th class="text-center">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($cart->total_items() > 0) {
                    $cartItems = $cart->contents();
                    foreach ($cartItems as $item) {
                        $nombreProducto = isset($item["Descripcion"]) ? $item["Descripcion"] : "Nombre no disponible";
                        $precio = isset($item["Precio"]) ? $item["Precio"] : 0;
                        $subtotal = isset($item["subtotal"]) ? $item["subtotal"] : 0;
                ?>
                        <tr>
                            <td class="align-middle"><?php echo htmlspecialchars($nombreProducto); ?></td>
                            <td class="align-middle"><?php echo '$' . number_format($precio, 2) . ' COP'; ?></td>
                            <td class="align-middle text-center">
                                <input type="number" class="form-control text-center w-50 mx-auto" value="<?php echo $item["qty"]; ?>" min="1" onchange="updateCartItem(this, '<?php echo $item["rowid"]; ?>')">
                            </td>
                            <td class="align-middle"><?php echo '$' . number_format($subtotal, 2) . ' COP'; ?></td>
                            <td class="align-middle text-center">
                                <a href="AccionCarta.php?action=removeCartItem&id=<?php echo $item["rowid"]; ?>" class="btn btn-danger" onclick="return confirm('¿Confirma eliminar?')">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="5" class="text-center">
                            <p class="text-muted">No has solicitado ningún producto...</p>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
            <?php if ($cart->total_items() > 0) { ?>
                <tfoot>
                    <tr>
                        <td>
                            <a href="consultarProducto.php" class="btn btn-warning">
                                <i class="bi bi-arrow-left"></i> Volver a la tienda
                            </a>
                        </td>
                        <td colspan="2"></td>
                        <td class="text-center">
                            <strong>Total: <?php echo '$' . number_format($cart->total(), 2) . ' COP'; ?></strong>
                        </td>
                        <td>
                            <a href="Pagos.php" class="btn btn-success btn-block">
                                Pagos <i class="bi bi-arrow-right"></i>
                            </a>
                        </td>
                    </tr>
                </tfoot>
            <?php } ?>
        </table>
    </div>
</div>

<?php include "../../src/templates/footer.php"; ?>
