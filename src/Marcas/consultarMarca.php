<?php

    session_start();

    $error = false;
    $config = include '../../data/config.php';

    try{
        $dsn = "mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"];
        $conexion = new PDO($dsn, $config["db"]["user"], $config["db"]["pass"], $config["db"]["options"]);

        $consultaSQL = "SELECT * FROM marca";

        $sentencia = $conexion -> prepare($consultaSQL);
        $sentencia -> execute();

        $listaMarca = $sentencia -> fetchAll();

    } catch(PDOException $error){
        $error = $error -> getMessage();
    }

?>

<?php include('../../src/templates/header.php');
      include '../../src/templates/navegador.php'; ?>


<h1 class="text-center mt-5 display-4">Consultar Marca</h1>

<?php
if (isset($_GET['error'])) {
    $mensaje = htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8');
    ?>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?php echo $mensaje ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<?php
if (isset($_GET['exito'])) {
    $mensaje = htmlspecialchars($_GET['exito'], ENT_QUOTES, 'UTF-8');
    ?>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-primary" role="alert">
                    <?php echo 'La Marca se ha eliminado Exitosamente'; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>




<?php 
    if ($error){
    ?>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

<div class="container">
<div class="row">
    <div class="col-md-12">
        <a href="./crearMarca.php" class="btn mt-4 btn-info">Crear Marca</a>
        <hr>
    </div>
</div>
</div>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mt-3" style="color:#961B71">
                Lista de marcas
            </h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($listaMarca && $sentencia->rowCount() > 0) {
                        foreach ($listaMarca as $fila){
                            ?>
                            <tr>
                                <td><?php echo $fila["IdMarca"]; ?></td>
                                <td><?php echo $fila["Nombre"]; ?></td>
                                <td>
                                <a class="btn btn-danger" href="<?= '../borrar.php?id='.$fila["IdMarca"].'&numero=',1 ?>" >Borrar</a>
                                </td>
                                <!-- <td>
                                    
                                    <a class="btn btn-success" href="<?= 'editar.php?id='.$fila["id"] ?> ">Editar</a>
                                </td> -->
                            </tr>
                        <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "../../src/templates/footer.php"; ?>