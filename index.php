<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wiigo Sport</title>
    <link rel="stylesheet" href="./public/css/index.css">
    <link rel="stylesheet" href="./public/assets/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</head>
<?php

session_start();

$config = include './data/config.php';


try {
    $dsn = "mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"];
    $conexion = new PDO($dsn, $config["db"]["user"], $config["db"]["pass"], $config["db"]["options"]);
} catch (PDOException $error) {
    $error = $error->getMessage();
}


if (isset($_SESSION['id'])) {



  $usuario = $_SESSION['id'];


 
  try {
      // Preparar la consulta para evitar inyección SQL
      $stmt = $conexion->prepare('SELECT * FROM persona WHERE IdPersona = :usuario');
      $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
      $stmt->execute();

      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      $persona = $user['Nombre'];
      $idRol = $user['IdRol'];
      $imagenPath = "../../public/img/" . $user['archivo_path'];

  } catch (PDOException $e) {
      // Manejo de errores en caso de fallas en la consulta
      exit('Error en la consulta: ' . $e->getMessage());
  }
} else {
  $persona = "Usuario";
}

?>



<link rel="stylesheet" href="/public/css/nav.css">

<nav class="navbar navbar-expand-lg p-4 bg-body-secondary py-3 mb-4"> 
    <div class="container d-flex flex-wrap align-items-center justify-content-between">
        <div class="col-md-3 mb-2 mb-md-0">
            <a href="/index.php" class="d-inline-flex link-body-emphasis text-decoration-none fs-3">
                <strong style="color: #961B71;">Wiigo Sport</strong>
            </a>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
                 <i class="fas fa-bars"></i>
            </span>
        </button>

        <div class="collapse navbar-collapse" style="color:black;" id="navbarNav">
        


        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
            <li><a href="/../index.php" class="nav-link px-2 text-dark">Principal</a></li>

            <?php
                if(isset($_SESSION['id'])&& $idRol==1){

              ?>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle px-2 text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Vendedores</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/../src/Rol/consultarVendedor.php">Consultar Empleado</a></li>
                    <li><a class="dropdown-item" href="/../src/Rol/crearEmpleado.php">Crear Empleado</a></li>
                    <li><a class="dropdown-item" href="/../src/Rol/consultarRol.php">Consultar Rol</a></li>
                </ul>
            </li>

            <?php
            }

            if(isset($_SESSION['id'])&& $idRol!=9){
            ?>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle px-2 text-dark" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">Productos</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/../src/carrito-master/consultarProducto.php">Consultar Producto</a></li>
                    <li><a class="dropdown-item" href="/../src/carrito-master/crearProducto.php">Crear Producto</a></li>
                </ul>
            </li>

            <?php
              }
              else{
            ?>

            <li>
                <a class="nav-link text-dark px-2" href="/../src/carrito-master/consultarProducto.php" role="button" aria-expanded="false">Productos</a>
            </li>

            <?php

            }
                if(isset($_SESSION['id'])&& $idRol==1){

              ?>

            <li class="nav-item dropdown">
                <a class="nav-link text-dark px-2" href="/../src/registroPedidos/registroPedidos.php" role="button" aria-expanded="false">Pedidos</a>
            </li>

            <?php 
                }

                if(isset($_SESSION['id'])&& $idRol!=9){
            ?>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle px-2 text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Otros..</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/../src/Marcas/consultarMarca.php">Consultar Marca</a></li>
                    <li><a class="dropdown-item" href="/../src/TipoCalzado/consultarTipoCalzado.php">Consultar Tipo de calzado</a></li>
                </ul>
            </li>

            <?php
                }
            ?>

            <li><a href="/../src/Ayuda/pqrsf.php" class="nav-link px-2 text-dark">Ayuda</a></li>
        </ul>

    </div>

        <div class="text-end col-md-3 mb-2 mb-md-0 d-flex align-items-center justify-content-end">
            <ul class="navbar-nav d-flex align-items-center"> 
                <li>
                  <a href="/../src/carrito-master/VerCarta.php" class="nav-link px-2 text-dark">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-fill" viewBox="0 0 16 16">
                      <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                    </svg>
                  </a>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link text-dark px-2" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false"></a>
                </li>
                <li class="nav-item dropdown d-flex align-items-center">
                    <?php if (isset($_SESSION['id'])) { ?>
                        <a class="nav-link me-2 dropdown-toggle" href="#" id="usuarioDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= $persona ?>
                        </a>
                        <a href="/src/Rol/perfil.php">
                          <img src="<?= $imagenPath ?>" alt="Perfil" class="rounded-circle" width="30" height="30" style="margin-left:15px;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="usuarioDropdown">  
                            <li><a class="dropdown-item" href="/src/InicioDeSesion/cerrar_sesion.php">Cerrar Sesión</a></li> 
                        </ul> 
                    <?php } else { ?>



                        <a class="nav-link me-2 dropdown-toggle" href="#" id="usuarioDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= $persona ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="usuarioDropdown"> 
                            <li>
                                <a class="dropdown-item" href="/src/InicioDeSesion/login.php">Inicia Sesión</a>
                            </li>
                        </ul>

                        <a href="/src/InicioDeSesion/login.php">
                          <img src="<?= isset($_GET['img']) ? htmlspecialchars($_GET['img']) : '/public/img/usuario.webp'?>" alt="Perfil" class="rounded-circle" width="30" height="30" style="margin-left:15px;">
                        </a>
                    <?php } ?>
                </li> 
            </ul>
        </div>
    </div>
</nav>

<body>

<div id="myCarousel" class="carousel slide mb-6" data-bs-ride="carousel" style="" >
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner" style="border-radius: 10px;height:500px; width:75%;margin-left:12%;margin-top:4%;">
        <div class="carousel-item active carousel" style="background-image:url(./public/img/nike.jpg);background-size:cover;">
        <div class="overlay"></div>
            <svg class="bd-placeholder-img" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="none"/></svg>
                <div class="container">
                    <div class="carousel-caption text-start" style="opacity:none;">
                        <h1 >¡Precio Y Calidad!</h1>
                        <p class="opacity-75"></p>
                        <p><a class="btn btn-lg btn" style="background-color:rgb(200, 46, 231);"  href="/src/InicioDeSesion/login.php">Registrate</a></p>
                    </div>
                </div>  
        </div>
        <div class="carousel-item active carousel" style="background-image:url(./public/img/zapatoscarrusel.png);background-size:cover;">
            <svg class="bd-placeholder-img" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="none"/></svg>
            <div class="container">
            <div class="overlay"></div>
                <svg class="bd-placeholder-img" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="none"/></svg>
                
            
            <div class="carousel-caption">
                    <h1>Promociones</h1>
                
                    <p><a class="btn btn-lg btn" style="background-color:rgb(200, 46, 231);" href="#">Conoce Mas</a></p>
                </div>
            </div>
        </div>
        <div class="carousel-item active carousel" style="background-image:url(./public/img/personal.jpg);background-size:cover;">
            <svg class="bd-placeholder-img" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="none"/></svg>
                <div class="container">
                    <div class="overlay"></div>
                    <svg class="bd-placeholder-img" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="none"/></svg>
                
                    <div class="carousel-caption text-end">
                        <h1>Nosotros</h1>
                        <p><a class="btn btn-lg btn" style="background-color:rgb(200, 46, 231);" href="#">Quiero Saber Mas</a></p>
                    </div>
                </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
</div>

<h2 class="titulos">Descubre</h2>

<div id="contenedor">
    <button class="desplazar-izquierda" onclick="desplazarIzquierda()">←</button>
    <div class="cajas-productos" id="cajasProductos">

    <?php
     
    $queryDestacados = $conexion->query("SELECT * FROM calzado WHERE destacado = 1 ORDER BY IdCalzado DESC LIMIT 10");

while ($row = $queryDestacados->fetch(PDO::FETCH_ASSOC)) {
    $imagenPath = "../../src/carrito-master/" . $row['archivo_path'];
    ?>
    <div class="descripcion">
        <div class="cajas" style="background-image:url('<?= $imagenPath; ?>');background-size:cover;"></div>
        <br>
        <h4>$<?= number_format($row['Precio'], 2, '.', ','); ?></h4>
        <p><?= htmlspecialchars($row['Descripcion']); ?></p>
    </div>
    <?php
}
?>
    </div>
    <button class="desplazar-derecha" onclick="desplazarDerecha()">→</button>
</div>

<script>
    function desplazarDerecha() {
    const contenedor = document.getElementById('cajasProductos');
    contenedor.scrollLeft += 150; // Ajusta el valor según la cantidad de desplazamiento deseada
}

    function desplazarIzquierda() {
    const contenedor = document.getElementById('cajasProductos');
    contenedor.scrollLeft -= 150; // Ajusta el valor según la cantidad de desplazamiento deseadaAjusta el valor según la cantidad de desplazamiento deseada
}
</script>
    
</body>
<?php include './src/templates/footer.php'; ?>

</html>
