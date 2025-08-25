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

// Obtener lista de roles para el filtro
$rolesQuery = $conexion->query("SELECT IdRol, Nombre FROM rol");
$roles = $rolesQuery->fetchAll(PDO::FETCH_ASSOC);

include '../../src/templates/header.php';
include '../../src/templates/navegador.php';
?>

<h1 class="text-center mt-5 mb-4 display-4">Personas</h1>

<div class="container px-5">
    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-md-3">
            <label for="filtroNombre" class="form-label fw-bold">Filtrar por Nombre:</label>
            <input type="text" class="form-control" id="filtroNombre" placeholder="Buscar nombre o apellido">
        </div>
        <div class="col-md-3">
            <label for="filtroDocumento" class="form-label fw-bold">Filtrar por Documento:</label>
            <input type="text" class="form-control" id="filtroDocumento" placeholder="Buscar documento">
        </div>
        <div class="col-md-3">
            <label for="filtroTelefono" class="form-label fw-bold">Filtrar por Teléfono:</label>
            <input type="text" class="form-control" id="filtroTelefono" placeholder="Buscar teléfono">
        </div>
        <div class="col-md-3">
            <label for="filtroRol" class="form-label fw-bold">Filtrar por Rol:</label>
            <select id="filtroRol" class="form-select">
                <option value="">Todos</option>
                <?php foreach ($roles as $rol): ?>
                    <option value="<?= $rol['Nombre'] ?>"><?= htmlspecialchars($rol['Nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Contenedor de personas -->
    <div class="row justify-content-center" id="personasContainer">
        <?php
        $query = $conexion->query("SELECT persona.*, rol.Nombre AS NombreRol FROM persona 
                                   JOIN rol ON persona.IdRol = rol.IdRol 
                                   ORDER BY persona.IdPersona DESC");
        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $imagenPath = "../../public/img/" . $row['archivo_path'];
        ?>
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4 persona-item"
            data-nombre="<?= strtolower($row['Nombre'] . ' ' . $row['Apellido']) ?>"
            data-documento="<?= $row['Documento'] ?>"
            data-telefono="<?= $row['Telefono'] ?>"
            data-rol="<?= strtolower($row['NombreRol']) ?>">
            <div class="card product-card">
                <div class="position-relative">
                    <img src="<?= $imagenPath; ?>" class="card-img-top product-image" 
                         alt="<?= htmlspecialchars($row['Nombre'] . ' ' . $row['Apellido']); ?>">
                    <div class="price-box">
                        <span class="fw-bold"> <?= htmlspecialchars($row["NombreRol"]); ?> </span>
                    </div>
                </div>
                <div class="card-body text-center product-info">
                    <h6 class="card-title fw-bold"> <?= htmlspecialchars($row["Nombre"] . " " . $row["Apellido"]); ?> </h6>
                    <p class="text-dark">Documento: <?= htmlspecialchars($row["Documento"]); ?></p>
                    <p class="text-dark">Teléfono: <?= htmlspecialchars($row["Telefono"]); ?></p>
                    <p class="text-dark">Correo: <?= htmlspecialchars($row["Correo"]); ?></p>
                </div>
            </div>
        </div>
        <?php }
        } else { ?>
            <p class="text-center text-muted">No hay personas registradas.</p>
        <?php } ?>
    </div>
</div>

<script>
document.querySelectorAll("#filtroNombre, #filtroDocumento, #filtroTelefono, #filtroRol").forEach(filtro => {
    filtro.addEventListener("input", function() {
        const nombreBuscado = document.getElementById("filtroNombre").value.toLowerCase();
        const documentoBuscado = document.getElementById("filtroDocumento").value.toLowerCase();
        const telefonoBuscado = document.getElementById("filtroTelefono").value.toLowerCase();
        const rolSeleccionado = document.getElementById("filtroRol").value.toLowerCase();

        document.querySelectorAll(".persona-item").forEach(item => {
            const nombre = item.getAttribute("data-nombre");
            const documento = item.getAttribute("data-documento");
            const telefono = item.getAttribute("data-telefono");
            const rol = item.getAttribute("data-rol");

            let mostrar = true;

            if (nombreBuscado && !nombre.includes(nombreBuscado)) {
                mostrar = false;
            }
            if (documentoBuscado && !documento.includes(documentoBuscado)) {
                mostrar = false;
            }
            if (telefonoBuscado && !telefono.includes(telefonoBuscado)) {
                mostrar = false;
            }
            if (rolSeleccionado && rol !== rolSeleccionado) {
                mostrar = false;
            }

            item.style.display = mostrar ? "block" : "none";
        });
    });
});
</script>

<style>
.product-card {
    transition: all 0.3s ease-in-out;
    border-radius: 10px;
    overflow: hidden;
    background-color: white;
    padding-bottom: 0;
    position: relative;
}

.product-image {
    height: 250px;
    object-fit: contain;
    width: 100%;
    transition: transform 0.3s ease-in-out;
}

.price-box {
    background-color: rgb(200, 46, 231);
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    font-weight: bold;
    position: absolute;
    bottom: 0;
    left: 0;
    transition: all 0.3s ease-in-out;
}

.product-info {
    background-color: white;
    padding: 10px 0;
    transition: all 0.3s ease-in-out;
}

.product-info p {
    transition: color 0.3s ease-in-out;
}

.product-card:hover {
    box-shadow: 0px 0px 10px rgb(200, 46, 231);
}

.product-card:hover .price-box {
    transform: translateY(-5px);
}
</style>

<?php include "../../src/templates/footer.php"; ?>
