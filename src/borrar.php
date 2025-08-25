
<?php
$config = include './../data/config.php';

try{
    $dsn = "mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"]. ";dbport=".$config["db"]["port"];
    $conexion = new PDO($dsn, $config["db"]["user"], $config["db"]["pass"], $config["db"]["options"]);

    if($numero=$_GET['numero']==1){

        $id = $_GET['id'];
        $consultaSQL = "DELETE FROM marca WHERE idMarca=".$id;

        $sentencia = $conexion -> prepare($consultaSQL);
        $sentencia->execute();

        header('Location: ./Marcas/consultarMarca.php?exito');
    }
    if($numero=$_GET['numero']==2){
        
        $id = $_GET['id'];
        $consultaSQL = "DELETE FROM tipoCalzado WHERE idTipoCalzado=".$id;

        $sentencia = $conexion -> prepare($consultaSQL);
        $sentencia->execute();

        header('Location: ./TipoCalzado/consultarTipoCalzado.php?exito');
    }
    if($numero=$_GET['numero']==3){
        
        $id = $_GET['id'];
        $consultaSQL = "DELETE FROM rol WHERE idRol=".$id;

        $sentencia = $conexion -> prepare($consultaSQL);
        $sentencia->execute();

        header('Location: ./Rol/consultarRol.php?exito');
    }
    if($numero=$_GET['numero']==4){
        $producto = $_GET['Id'];

        $consultaSQL = "DELETE FROM calzado WHERE IdCalzado=".$producto;

        $sentencia = $conexion -> prepare($consultaSQL);
        $sentencia->execute();

        header('location: ./carrito-master/consultarProducto.php');
    }
    
    

}catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
    header('Location: ./marcas/consultarMarca.php?error='.$resultado['mensaje']);
}
?>



