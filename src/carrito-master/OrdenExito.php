<?php
session_start();

// Verifica si se ha recibido el ID del pedido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: consultarProducto.php");
    exit();
}

$idPedido = intval($_GET['id']);

?>

<?php include('../templates/header.php');
      include '../templates/navegador.php'; ?>

<div class="container mt-5 text-center">
    <div class="card shadow-lg">
        <div class="card-body">
            <h1 class="text-success">🎉 ¡Pedido Completado! 🎉</h1>
            <p class="fs-5">Gracias por tu compra. Hemos recibido tu pedido y lo estamos procesando.</p>
            
            <div class="alert alert-info">
                <h4 class="fw-bold mb-0">ID de Pedido: <span class="text-primary"><?php echo htmlspecialchars($idPedido); ?></span></h4>
            </div>
            <p class="text-muted">Recibirás una confirmación en tu correo.</p>
            <a href="consultarProducto.php" class="btn btn-primary mt-3">
                <i class="bi bi-arrow-left"></i> Volver a la tienda
            </a>
        </div>
    </div>
</div>

<?php include "../../src/templates/footer.php"; ?>
