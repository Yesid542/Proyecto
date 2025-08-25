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

// Obtener lista de tipos de calzado
$tiposQuery = $conexion->query("SELECT IdTipoCalzado, Nombre FROM tipoCalzado");
$tiposCalzado = $tiposQuery->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de marcas
$marcasQuery = $conexion->query("SELECT IdMarca, Nombre FROM marca");
$marcas = $marcasQuery->fetchAll(PDO::FETCH_ASSOC);

$precioMaximo = 200000; // Precio máximo estático

include '../../src/templates/header.php';
include '../../src/templates/navegador.php';
?>

<link rel="stylesheet" href="../../public/css/productos.css">

<h1 class="text-center mt-5 mb-4 display-4">Productos</h1>

<div class="container px-5">
    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-md-4">
            <label for="filtroTipo" class="form-label fw-bold">Filtrar por tipo de calzado:</label>
            <select id="filtroTipo" class="form-select">
                <option value="">Todos</option>
                <?php foreach ($tiposCalzado as $tipo): ?>
                    <option value="<?= $tipo['IdTipoCalzado'] ?>"><?= htmlspecialchars($tipo['Nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label for="filtroMarca" class="form-label fw-bold">Filtrar por marca:</label>
            <select id="filtroMarca" class="form-select">
                <option value="">Todas</option>
                <?php foreach ($marcas as $marca): ?>
                    <option value="<?= $marca['IdMarca'] ?>"><?= htmlspecialchars($marca['Nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label for="filtroPrecio" class="form-label fw-bold">Filtrar por precio (hasta $<span id="precioValor"><?= number_format($precioMaximo, 0, ',', '.') ?></span>):</label>
            <input type="range" class="form-range" id="filtroPrecio" min="0" max="<?= $precioMaximo ?>" step="5000" value="<?= $precioMaximo ?>">
        </div>
    </div>

    <!-- Contenedor de productos -->
    <div class="row justify-content-center" id="productosContainer">
        <?php
        $query = $conexion->query("SELECT c.*, tc.Nombre as TipoCalzado FROM calzado c 
                                   INNER JOIN tipoCalzado tc ON c.IdTipoCalzado = tc.IdTipoCalzado 
                                   ORDER BY c.IdCalzado DESC ");
        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $imagenPath = "../../src/carrito-master/" . $row['archivo_path'];
        ?>
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4 producto-item"
                data-tipo="<?= $row['IdTipoCalzado'] ?>"
                data-marca="<?= $row['IdMarca'] ?>"
                data-precio="<?= $row['Precio'] ?>">
                <div class="card product-card">
                            <div class="position-relative" >
                            <?php
                                if(isset($_SESSION['id'])&&$user['IdRol']!=9){

                                ?>

                                    <div class="button-container">
                                        <a href="#" class="btn btn-success position-absolute cart-icon mainButton">
                                            <i>+</i>
                                        </a>

                                        <div class="sidePanel hidden" style="border-radius:15%;margin-top:10px;">
                                            <a style="text-decoration:none;" href="./editarProducto.php?id=<?= $row['IdCalzado'];?>">
                                                <button class="btn editButton" style="background-color:purple;border-radius:50%;">
                                                    <i class="bi bi-pencil-fill"></i> <!-- Ícono de lápiz -->
                                                </button>
                                            </a>
                                            <a href="../borrar.php?numero=4&Id=<?=$row['IdCalzado']?>">
                                                <button class="btn deleteButton" style="background-color: rgb(158, 32, 32);border-radius:50%;">
                                                    <i class="bi bi-trash-fill"></i> <!-- Ícono de papelera -->
                                                </button>
                                            </a>
                                            <a href="marcarDestacado.php?IdCalzado=<?= $row['IdCalzado']; ?>">
                                                <button class="btn deleteButton" style="background-color: <?= $row['destacado'] ? 'gold' : 'rgb(192, 209, 35)'; ?>; border-radius: 50%;">
                                                    <i class="bi bi-star-fill"></i>
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                <?php
                                }else{
                                    ?>
                                    <a href="productoUnico.php?IdCalzado=<?= $row['IdCalzado'];?>"
                                         class="btn btn-success position-absolute cart-icon">
                                      <i class="bi bi-cart-plus-fill fs-5"></i></a>
                                <?php      
                                }

                                ?>  

                            <a style="text-decoration:none;" href="./productoUnico.php?IdCalzado=<?= $row['IdCalzado'];?>">
                                <!-- Imagen -->
                                <img src="<?php echo $imagenPath; ?>" class="card-img-top product-image" 
                                     alt="<?php echo htmlspecialchars($row['Descripcion']); ?>">
                                <div class="price-box">
                                    <span class="fw-bold">$<?php echo number_format($row['Precio'], 2, '.', ','); ?></span>
                                </div>
                            </div>
                            <div class="card-body text-center product-info">
                                <h6 class="card-title fw-bold"><?php echo htmlspecialchars($row["Descripcion"]); ?></h6>
                                <p class="text-dark"><?php echo htmlspecialchars($row["TipoCalzado"]); ?></p>
                            </div>
                            </a>
                        </div>
            </div>
        <?php }
        } else { ?>
            <p class="text-center text-muted">No hay productos disponibles.</p>
        <?php } ?>
    </div>
</div>

<script>
document.querySelectorAll("#filtroTipo, #filtroMarca, #filtroPrecio").forEach(filtro => {
    filtro.addEventListener("input", function() {
        const tipoSeleccionado = document.getElementById("filtroTipo").value;
        const marcaSeleccionada = document.getElementById("filtroMarca").value;
        const precioSeleccionado = document.getElementById("filtroPrecio").value;

        document.getElementById("precioValor").textContent = new Intl.NumberFormat("es-CO").format(precioSeleccionado);

        document.querySelectorAll(".producto-item").forEach(item => {
            const tipo = item.getAttribute("data-tipo");
            const marca = item.getAttribute("data-marca");
            const precio = parseFloat(item.getAttribute("data-precio"));

            let mostrar = true;

            if (tipoSeleccionado && tipo !== tipoSeleccionado) {
                mostrar = false;
            }
            if (marcaSeleccionada && marca !== marcaSeleccionada) {
                mostrar = false;
            }
            if (precio > precioSeleccionado) {
                mostrar = false;
            }

            item.style.display = mostrar ? "block" : "none";
        });
    });
});


document.querySelectorAll(".mainButton").forEach(function(button) {
        button.addEventListener("click", function(event) {
            event.preventDefault(); // Prevenir el comportamiento predeterminado
            const panel = this.closest(".product-card").querySelector(".sidePanel");
            panel.classList.toggle("hidden");
            panel.classList.toggle("active");
        });
    });

</script>

<?php include "../../src/templates/footer.php"; ?>
