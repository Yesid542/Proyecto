<?php

session_start();

if (isset($_POST['submit'])) {
  $resultado = [
    'error' => false,
    'mensaje' => 'El tipo de calzado ha sido agregado con éxito'
  ];

  $config = include '../../data/config.php';

  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $tipoCalzado = [
      "nombre"   => $_POST['nombre']
    ];

    $consultaSQL = "INSERT INTO tipocalzado (nombre) values (:" . implode(", :", array_keys($tipoCalzado)) . ")";


    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute($tipoCalzado);

  } catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
  }
}
?>

<?php include('../templates/header.php');
      include '../templates/navegador.php'; ?>


<div class="d-flex align-items-center justify-content-center pt-5 mt-5 ">
    <div class="container rounded overflow-hidden d-flex" style="max-width: 900px; height: 500px;">
        <!-- Lado izquierdo -->
        <div class="left-side flex-fill">
            <img src="../../public/img/zapatoMarca.png" alt="Zapatos" class="img-fluid">
        </div>
        <!-- Lado derecho -->
        <div class="right-side flex-fill d-flex flex-column justify-content-center align-items-center p-4">

               <?php
                if (isset($resultado)) {
                  ?>
                  <div class="container mt-3">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="alert alert-<?= $resultado['error'] ? 'danger' : 'success' ?>" role="alert">
                          <?= $resultado['mensaje'] ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php
                }
                ?> 


            <h1 class="text-center mb-4">Crea un tipo de calzado</h1>

            <a href="./consultarTipoCalzado.php" class="btn mt-4 texto-botones" id="atras">Atras</a>
            <form class="w-100" style="max-width: 300px;" method="post">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" placeholder="Ingresa el nombre" name="nombre">
                </div>
                <button type="submit" name="submit"  class="btn btn-custom w-100 texto-botones">Crear</button>
            </form>
            <a href="#" class="mt-3">¿Ya existe la que necesitas...?</a>
        </div>
    </div>

    
</div>

<?php include '../templates/footer.php'?>