<?php
session_start();

$error = false;
$config = include '../../data/config.php';

try {
    $dsn = "mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"];
    $conexion = new PDO($dsn, $config["db"]["user"], $config["db"]["pass"], $config["db"]["options"]);
} catch (PDOException $error) {
    $error = $error->getMessage();
}

if (isset($_GET['IdCalzado']) && is_numeric($_GET['IdCalzado'])) {
    $idCalzado = $_GET['IdCalzado'];
    $query = $conexion->prepare("SELECT c.*, tc.Nombre as TipoCalzado FROM calzado c INNER JOIN tipoCalzado tc ON c.IdTipoCalzado = tc.IdTipoCalzado WHERE c.IdCalzado = :idCalzado");
    $query->execute(['idCalzado' => $idCalzado]);
    $producto = $query->fetch(PDO::FETCH_ASSOC);
}
?>

<?php 
include '../../src/templates/header.php';
include '../../src/templates/navegador.php'; 
?>

<style>
    body {
        font-family: 'Poppins', sans-serif;
    }

    .container {
        max-width: 1000px;
    }

    .product-img {
    max-width: 100%;
    height: 400px;
    object-fit: contain;
    border: 2px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    background-color: #fff;
    display: block;
    padding: 10px; 
    }


    .talla-btn {
        font-size: 1.2rem;
        border-radius: 10px;
        transition: all 0.2s ease-in-out;
        background-color:rgb(76, 76, 76);
    }

    .talla-btn:hover {
        background-color: #000;
        color: #fff;
    }

    .talla-seleccionada {
        background-color: #000 !important;
        color: #fff !important;
    }

    .btn-dark {
        font-size: 1.2rem;
        border-radius: 10px;
        transition: all 0.1s ease-in-out;
    }

    .btn-dark:hover {
        background-color:rgb(0, 0, 0);
    }
</style>

<div class="container mt-4 mb-5 pt-5 pb-5">
    <div class="row">
         <div class="col-12 mb-3">
            <a href="consultarProducto.php" class="btn btn-warning">
                ⬅ Volver a Consultar Producto
            </a>
        </div>


        <div class="col-md-7 text-center">
            <img src="../../src/carrito-master/<?php echo $producto['archivo_path']; ?>" 
                 class="product-img" 
                 alt="<?php echo htmlspecialchars($producto['Descripcion']); ?>">
        </div>
        <div class="col-md-5 d-flex flex-column justify-content-center">
            <h2 class="fw-bold" style="font-size: 1.8rem;"> 
                <?php echo htmlspecialchars($producto['Descripcion']); ?> 
            </h2>
            <p class="text-muted" style="font-size: 1.2rem;"> 
                <?php echo htmlspecialchars($producto['TipoCalzado']); ?> 
            </p>
            <h4 class="fw-bold text-dark" style="font-size: 1.5rem;">
                $<?php echo number_format($producto['Precio'], 2, '.', ','); ?>
            </h4>

            <div class="mt-3">
                <p class="fw-bold" style="font-size: 1.3rem;">Selecciona tu talla</p>
                <div class="row">
                    <?php 
                    $tallas = range(36, 44);
                    foreach ($tallas as $talla) { ?>
                        <div class="col-4 mb-2">
                            <button class="btn btn-outline-light  w-100 py-2 talla-btn" 
                                    data-talla="<?php echo $talla; ?>">
                                EU <?php echo $talla; ?>
                            </button>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <input type="hidden" id="selectedTalla" name="talla" value="">

            <a href="AccionCarta.php?action=addToCart&IdCalzado=<?php echo $producto['IdCalzado']; ?>" 
               class="btn btn-dark mt-3 w-100 py-3 shadow-lg">
               Añadir al carrito
            </a>
            <p class="text mt-3 fw-bold" style="font-size: 1rem;">
                Este producto queda excluido de las promociones y descuentos del sitio web.
            </p>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".talla-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                document.querySelectorAll(".talla-btn").forEach(b => b.classList.remove("talla-seleccionada"));
                this.classList.add("talla-seleccionada");
                document.getElementById("selectedTalla").value = this.getAttribute("data-talla");
            });
        });
    });
</script>

<?php include "../../src/templates/footer.php"; ?>
