<?php 

session_start();

include('../../src/templates/header.php');
include '../../src/templates/navegador.php';

// Configuración de la base de datos
$host = "localhost";
$usuario = "root"; 
$clave = ""; 
$base_datos = "wiigoinventario";

// Conexión a la base de datos
$conn = new mysqli($host, $usuario, $clave, $base_datos);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

?>

<h1 class="text-center mt-5 mb-5 display-4">Formulario PQRSF</h1>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $tipo = $_POST['tipo'];
    $mensaje = $_POST['mensaje'];

    $archivo_nombre = "";
    $archivo_path = "";

    if (!empty($_FILES['archivo']['name'])) {
        $archivo_nombre = basename($_FILES['archivo']['name']);
        $directorio = "uploads/";
        $archivo_path = $directorio . $archivo_nombre;

        if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $archivo_path)) {
            die("Error al subir el archivo.");
        }
    }

    $sql = "INSERT INTO pqrsf (nombre, email, telefono, tipo, mensaje, archivo_nombre, archivo_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $nombre, $email, $telefono, $tipo, $mensaje, $archivo_nombre, $archivo_path);

    if ($stmt->execute()) {
        echo '<div class="alert alert-success text-center">Solicitud enviada correctamente.</div>';
    } else {
        echo '<div class="alert alert-danger text-center">Error: ' . $stmt->error . '</div>';
    }

    $stmt->close();
    $conn->close();
}
?>

<div class="container my-5 py-2">
    <div class="shadow-lg p-4 bg-secondary-subtle">
        <form action="pqrsf.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control rounded-pill" id="nombre" name="nombre" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control rounded-pill" id="email" name="email" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="tel" class="form-control rounded-pill" id="telefono" name="telefono">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tipo" class="form-label">Tipo de Solicitud</label>
                    <select class="form-select rounded-pill" id="tipo" name="tipo" required>
                        <option value="">Seleccione...</option>
                        <option value="Petición">Petición</option>
                        <option value="Queja">Queja</option>
                        <option value="Reclamo">Reclamo</option>
                        <option value="Sugerencia">Sugerencia</option>
                        <option value="Felicitación">Felicitación</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="mensaje" class="form-label">Mensaje</label>
                <textarea class="form-control rounded-3" id="mensaje" name="mensaje" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="archivo" class="form-label">Adjuntar Archivo (Opcional)</label>
                <input class="form-control" type="file" id="archivo" name="archivo">
            </div>

            <button type="submit" class="btn w-100 text-white rounded-pill" style="background-color:#961B71">Enviar Solicitud</button>
        </form>
    </div>
</div>

<?php include "../../src/templates/footer.php"; ?>
