<?php

session_start();
$error = false;
$config = include "../../data/config.php";

try {
    $dsn = "mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"];
    $conexion = new PDO($dsn, $config["db"]["user"], $config["db"]["pass"], $config["db"]["options"]);

    // Obtener marcas y tipos de calzado
    $listaMarca = $conexion->query("SELECT * FROM marca")->fetchAll();
    $listaTipoCalzado = $conexion->query("SELECT * FROM tipoCalzado")->fetchAll();
} catch (PDOException $error) {
    $error = $error->getMessage();
}

if (isset($_POST['submit'])) {
    $resultado = ['error' => false, 'mensaje' => 'El producto ha sido agregado con éxito'];

    try {
        // Conectar a la BD
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

        // Manejo de imagen
        $archivo_nombre = "";
        $archivo_path = "";

        if (!empty($_FILES['archivo']['name'])) {
            $archivo_nombre = time() . "_" . basename($_FILES['archivo']['name']);
            $directorio = __DIR__ . "/../../src/carrito-master/uploads/"; // Ruta absoluta

            // Crear la carpeta si no existe
            if (!is_dir($directorio)) {
                mkdir($directorio, 0777, true);
            }

            $archivo_destino = $directorio . $archivo_nombre;

            // Mover archivo y guardar la ruta relativa
            if (move_uploaded_file($_FILES['archivo']['tmp_name'], $archivo_destino)) {
                $archivo_path = "uploads/" . $archivo_nombre;
            } else {
                throw new Exception("Error al subir la imagen.");
            }
        }

        // Insertar en la BD
        $consultaSQL = "INSERT INTO calzado (IdTipoCalzado, IdMarca, Genero, Precio, Descripcion, archivo_nombre, archivo_path) 
                        VALUES (:IdTipoCalzado, :IdMarca, :Genero, :Precio, :Descripcion, :archivo_nombre, :archivo_path)";
        $sentencia = $conexion->prepare($consultaSQL);
        $sentencia->execute([
            "IdTipoCalzado" => $_POST['TipoCalzado'],
            "IdMarca" => $_POST['Marca'],
            "Genero" => $_POST['Genero'],
            "Precio" => $_POST['Precio'],
            "Descripcion" => $_POST['Descripcion'],
            "archivo_nombre" => $archivo_nombre,
            "archivo_path" => $archivo_path
        ]);
    } catch (Exception $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = $error->getMessage();
    }
}
?>

<?php include '../templates/header.php'; ?>
<?php include '../templates/navegador.php'; ?>

<div class="d-flex align-items-center justify-content-center pt-5 mt-5">
    <div class="container rounded overflow-hidden d-flex" style="max-width: 900px;">
        <!-- Imagen de referencia a la izquierda -->
        <div class="left-side flex-fill">
            <img src="../../public/img/zapatoMarca.png" alt="Zapatos" class="img-fluid">
        </div>

        <!-- Formulario a la derecha -->
        <div class="right-side flex-fill d-flex flex-column justify-content-center align-items-center p-4">
            <?php if (isset($resultado)) { ?>
                <div class="container mt-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-<?= $resultado['error'] ? 'danger' : 'success' ?>" role="alert">
                                <?= $resultado['mensaje'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?> 

            <h1 class="text-center mb-4">Crear producto</h1>
            <a href="./consultarProducto.php" class="btn mt-4 texto-botones" id="atras">Atrás</a>

            <form class="w-100" style="max-width: 300px;" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="TipoCalzado" class="form-label">Tipo de calzado:</label>
                    <select name="TipoCalzado" id="TipoCalzado" class="form-select">
                        <option value="">Selecciona una opción...</option>
                        <?php foreach ($listaTipoCalzado as $fila) { ?>
                            <option value="<?= $fila["IdTipoCalzado"]; ?>"><?= $fila["Nombre"]; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="Marca" class="form-label">Marca:</label>
                    <select name="Marca" id="Marca" class="form-select">
                        <option value="">Selecciona una opción...</option>
                        <?php foreach ($listaMarca as $fila) { ?>
                            <option value="<?= $fila["IdMarca"]; ?>"><?= $fila["Nombre"]; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="Genero" class="form-label">Género:</label>
                    <select name="Genero" id="Genero" class="form-select">
                        <option value="">Selecciona una opción...</option>
                        <option value="M">Hombre</option>
                        <option value="F">Mujer</option>
                        <option value="U">Unisex</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="Precio" class="form-label">Precio:</label>
                    <input type="number" class="form-control" id="Precio" name="Precio" placeholder="Ingresa el precio">
                </div>

                <div class="mb-3">
                    <label for="Descripcion" class="form-label">Descripción:</label>
                    <textarea class="form-control" name="Descripcion" id="Descripcion"></textarea>
                </div>

                <div class="mb-3">
                    <label for="archivo" class="form-label">Adjuntar Imagen (Opcional)</label>
                    <input class="form-control" type="file" id="archivo" name="archivo">
                </div>

                <button type="submit" name="submit" class="btn btn-custom w-100 texto-botones">Crear</button>
            </form>
            <a href="#" class="mt-3">¿Ya existe la que necesitas...?</a>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
