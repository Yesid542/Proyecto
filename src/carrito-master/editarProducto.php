<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="../../public/css/editarProducto.css">

</head>


<body>
    



    <?php
    session_start();


    include '../../src/templates/header.php';
    include '../../src/templates/navegador.php'; 
    $error = false;
    $config = include '../../data/config.php';

    try {
        $dsn = "mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"];
        $conexion = new PDO($dsn, $config["db"]["user"], $config["db"]["pass"], $config["db"]["options"]);
    } catch (PDOException $error) {
        $error = $error->getMessage();
    }
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $idCalzado = $_GET['id'];
    }
    
    try{
        $query = $conexion->prepare("SELECT c.*, tc.Nombre as TipoCalzado FROM calzado c INNER JOIN tipoCalzado tc ON c.IdTipoCalzado = tc.IdTipoCalzado WHERE c.IdCalzado = :idCalzado");
        $query->execute(['idCalzado' => $idCalzado]);
        $producto = $query->fetch(PDO::FETCH_ASSOC);   
    }catch (PDOException $error) {
        $error = $error->getMessage();
    }


    


    ?>

    <div class="container mt-4 mb-5 pt-5 pb-5">
        <div class="row">
             <div class="col-12 mb-3">
                <a href="./consultarProducto.php" class="btn btn-warning">
                    ⬅ Volver a Consultar Producto
                </a>
            </div>


            <div class="col-md-7 text-center">
                <!-- Imagen para mostrar la vista previa -->
                <form id="formularioImagenProducto" action="../editar.php" method="POST" enctype="multipart/form-data">
                    <img id="product-image" src="../../src/carrito-master/<?php echo $producto['archivo_path']; ?>" 
                        class="product-img" 
                        alt="<?php echo htmlspecialchars($producto['Descripcion']); ?>">
                    <input type="file" id="imagen" name="imagen" accept="image/*" onchange="previewImage(event); enviarFormulario();" style="display: none;">
                    <input type="hidden" name="producto">
                    <input type="hidden" name="id" value="<?=$idCalzado?>">
                </form>

                <!-- Botón para actualizar la imagen -->
                <button id="img" type="button" onclick="document.getElementById('imagen').click()">Actualizar Imagen</button>

                <!-- Input oculto para seleccionar la imagen -->
                <form action="">
                    <input id="imagen" type="file" style="display:none;" accept="image/*" onchange="mostrarVistaPrevia(event)">
                </form>    
            </div>


            <div  class="col-md-5 d-flex flex-column justify-content-center">
                <div id="productInfo">
                    <h2 class="fw-bold" style="font-size: 1.8rem;"> 
                        <span id="descripcion"><?php echo $producto['Descripcion']; ?></span> 
                    </h2>
                    <p class="text-muted" style="font-size: 1.2rem;"> 
                        <span id="tipo"><?php echo htmlspecialchars($producto['TipoCalzado']); ?> </span>
                    </p>
                    <h4 class="fw-bold text-dark" style="font-size: 1.5rem;">
                    $<span id="precio"><?php echo number_format($producto['Precio'], 2, '.', ','); ?></span>
                    </h4>
                </div>

                <div class="mt-3">
                    <p class="fw-bold" style="font-size: 1.3rem;">Selecciona tu talla</p>
                    <div class="row">
                        <?php 
                        $tallas = range(36, 44);
                        foreach ($tallas as $talla) { ?>
                            <div class="col-4 mb-2">
                                <button class="btn btn-outline-dark  w-100 py-2 talla-btn" 
                                        data-talla="<?php echo $talla; ?>">
                                    EU <?php echo $talla; ?>
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <input type="hidden" id="selectedTalla" name="talla" value="">
                

                <div class="profile-info">
                    <a class="btn btn-primary" onclick = "toggleEditSave(),lista()" id="editButton">Editar Producto</a>
                    <a href="" class="btn btn-danger">Cancelar</a>
                </div>
                <p class="text mt-3 fw-bold" style="font-size: 1rem;">
                    Este producto queda excluido de las promociones y descuentos del sitio web.
                </p>
            </div>
        </div>
    </div>

    <script>

        function lista(){

            fetch('opciones.php')
                .then(response => response.text())
                .then(optionsHTML => {
                    document.getElementById('tipoSelect').innerHTML = optionsHTML;
                });
        }
        
        function toggleEditSave() {

                

                const isEditing = document.getElementById("editButton").textContent === "Editar Producto";
                const productInfo = document.getElementById("productInfo");

            if (isEditing) {
                // Convert to input fields for editing
                productInfo.innerHTML = `
                    <h2 class="fw-bold" style="font-size: 1.8rem;"> <input type="text" id="descripcionInput" value="${document.getElementById('descripcion').textContent}"></h2>
                    <p class="text-muted" style="font-size: 1.2rem;">
                        <select id="tipoSelect"></select> </p>
                     <h4 class="fw-bold text-dark" style="font-size: 1.5rem;"><input id="precioInput" value="${document.getElementById('precio').textContent}"></h4>  
                `;

                document.getElementById("editButton").textContent = "Guardar";
    } else {
        const descripcionValue = document.getElementById("descripcionInput").value;
        const tipoValue = document.getElementById("tipoSelect").value;
        const precioValue = document.getElementById("precioInput").value;

        window.location.href = `../editar.php?accion=producto&id=<?=$idCalzado?>&descripcion=${encodeURIComponent(descripcionValue)}&nombreTipo=${encodeURIComponent(tipoValue)}&precio=${encodeURIComponent(precioValue)}`;
        }}


        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".talla-btn").forEach(btn => {
                btn.addEventListener("click", function() {
                    document.querySelectorAll(".talla-btn").forEach(b => b.classList.remove("talla-seleccionada"));
                    this.classList.add("talla-seleccionada");
                    document.getElementById("selectedTalla").value = this.getAttribute("data-talla");
                });
            });
        });
        function mostrarVistaPrevia(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Actualizar la fuente de la imagen para mostrar la vista previa
                document.getElementById('imagenPreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]); // Leer el archivo seleccionado
        }
    }

    
    function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
            const output = document.getElementById('product-image');
            output.src = reader.result; // Previsualizar imagen
        };
        reader.readAsDataURL(event.target.files[0]);

    // Enviar el formulario automáticamente
            enviarFormulario();
        }


        function enviarFormulario() {
            document.getElementById('formularioImagenProducto').submit(); // Enviar el formulario automáticamente.
        }

        window.onload = function() {
            const storedImageUrl = localStorage.getItem('userImage');
            if (storedImageUrl) {
                document.getElementById('product-image').src = storedImageUrl; // Mostrar la imagen desde localStorage
            }
        };

        function saveImage() {
            const imageUrl = document.getElementById('hidden-image-url').value;
            document.getElementById('product-image').src = imageUrl; // Actualizar la imagen
            localStorage.setItem('userImage', imageUrl); // Guardar en localStorage
        }

    </script>

    <?php include "../../src/templates/footer.php"; 
    ?>


</body>
</html>