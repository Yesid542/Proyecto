<?php
date_default_timezone_set("America/Bogota");

// Iniciar la sesión
session_start();

// Incluir la clase del carrito y la configuración
include 'La-carta.php';
$cart = new Cart;
include 'Configuracion.php';

// Verificar que haya una acción válida
if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])) {

    if($_REQUEST['action'] == 'addToCart' && !empty($_REQUEST['IdCalzado'])) {
        $productID = $_REQUEST['IdCalzado'];

        // Obtener detalles del producto
        $query = $conexion->query("SELECT * FROM calzado WHERE IdCalzado = ".$productID);
        $row = $query->fetch(PDO::FETCH_ASSOC);

        
        $itemData = array(
            'IdCalzado' => $row['IdCalzado'],
            'Descripcion' => $row['Descripcion'],
            'Precio' => $row['Precio'], // Corregido, antes estaba 'price'
            'qty' => 1
        );

        $insertItem = $cart->insert($itemData);
        $redirectLoc = $insertItem ? 'VerCarta.php' : 'consultarProducto.php';
        header("Location: ".$redirectLoc);

    } elseif ($_REQUEST['action'] == 'updateCartItem' && !empty($_REQUEST['IdCalzado'])) {
        $itemData = array(
            'rowid' => $_REQUEST['id'],
            'qty' => $_REQUEST['qty']
        );
        $updateItem = $cart->update($itemData);
        echo $updateItem ? 'ok' : 'err';
        die;

    } elseif ($_REQUEST['action'] == 'removeCartItem' && !empty($_REQUEST['id'])) {
        $deleteItem = $cart->remove($_REQUEST['id']);
        header("Location: VerCarta.php");

    } elseif ($_REQUEST['action'] == 'placeOrder' && $cart->total_items() > 0 && !empty($_SESSION['idPersona'])) {
        // Insertar en la tabla 'recibo'
        $idPersona = $_SESSION['idPersona'];
        $total = $cart->total();

        $insertRecibo = $db->query("INSERT INTO recibo (IdPersona, PrecioTotal) VALUES ('$idPersona', '$total')");

        if ($insertRecibo) {
            $IdRecibo = $db->insert_id;
            $sql = '';

            // Insertar en la tabla 'detalleRecibo'
            $cartItems = $cart->contents();
            foreach ($cartItems as $item) {
                $sql .= "INSERT INTO detalleRecibo (IdRecibo, IdCalzado, Cantidad) VALUES ('$IdRecibo', '".$item['IdCalzado']."', '".$item['qty']."');";
            }

            // Ejecutar múltiples consultas
            $insertDetalleRecibo = $db->multi_query($sql);

            if ($insertDetalleRecibo) {
                $cart->destroy();
                header("Location: OrdenExito.php?id=$IdRecibo");
            } else {
                header("Location: Pagos.php");
            }
        } else {
            header("Location: Pagos.php");
        }

    } else {
        header("Location: consultarProducto.php");
    }

} else {
    header("Location: consultarProducto.php");
}
?>
