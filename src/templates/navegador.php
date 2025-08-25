<?php

//session_start();

if (isset($_SESSION['id'])) {
    $usuario = $_SESSION['id'];

    $config = include './../../data/config.php';

    try {
        $dsn = "mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"];
        $conexion = new PDO($dsn, $config["db"]["user"], $config["db"]["pass"], $config["db"]["options"]);
    } catch (PDOException $error) {
        $error = $error->getMessage();
    }

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

<link rel="stylesheet" href="../../public/css/nav.css">

<nav class="navbar navbar-expand-lg p-4 bg-body-secondary py-3 mb-4"> 
    <div class="container d-flex flex-wrap align-items-center justify-content-between">
        <div class="col-md-3 mb-2 mb-md-0">
            <a href="/../index.php" class="d-inline-flex link-body-emphasis text-decoration-none fs-3">
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
                    <a class="nav-link dropdown-toggle px-2 text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Productos</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/../src/carrito-master/consultarProducto.php">Consultar Producto</a></li>
                        <li><a class="dropdown-item" href="/../src/carrito-master/crearProducto.php">Crear Producto</a></li>
                    </ul>
                </li>

                <?php
                  }
                  else{
                ?>

                <li class="nav-item dropdown">
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
                            <a href="../Rol/perfil.php">
                              <img src="<?= $imagenPath ?>" alt="Perfil" class="rounded-circle" width="30" height="30" style="margin-left:15px;">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="usuarioDropdown">  
                                <li><a class="dropdown-item" href="../InicioDeSesion/cerrar_sesion.php">Cerrar Sesión</a></li> 
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

                            <a href="../InicioDeSesion/login.php">
                              <img src="<?= isset($_GET['img']) ? htmlspecialchars($_GET['img']) : '/public/img/usuario.webp'?>" alt="Perfil" class="rounded-circle" width="30" height="30" style="margin-left:15px;">
                            </a>
                        <?php } ?>
                    </li> 
                </ul>
            </div>
        
    </div>
</nav>