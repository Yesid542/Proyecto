<?php
session_start();

include './Configuracion.php';

$sql = "SELECT dr.IdDetalleRecibo, r.IdRecibo, p.Nombre AS Cliente, c.Descripcion AS Producto, dr.Cantidad 
        FROM detalleRecibo dr
        JOIN recibo r ON dr.IdRecibo = r.IdRecibo
        JOIN persona p ON r.IdPersona = p.IdPersona
        JOIN calzado c ON dr.IdCalzado = c.IdCalzado";

$stmt = $conexion->prepare($sql);
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);


include '../../src/templates/header.php';
include '../../src/templates/navegador.php';
?>


    <div class="container mt-5">
        <h2 class="text-center mb-4">Pedidos</h2>

        <a href="./exportar_excel.php" class="btn btn-success btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-file-earmark-excel"></i> Exportar
        </a>


        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID Detalle</th>
                    <th>ID Recibo</th>
                    <th>Cliente</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($pedidos) > 0): ?>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?= htmlspecialchars($pedido["IdDetalleRecibo"]) ?></td>
                            <td><?= htmlspecialchars($pedido["IdRecibo"]) ?></td>
                            <td><?= htmlspecialchars($pedido["Cliente"]) ?></td>
                            <td><?= htmlspecialchars($pedido["Producto"]) ?></td>
                            <td><?= htmlspecialchars($pedido["Cantidad"]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No hay pedidos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>


    <?php include "../../src/templates/footer.php"; ?>
