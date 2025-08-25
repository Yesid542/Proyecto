<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario Responsivo</title>
    <link rel="stylesheet" href="../../public/css/newRegistra.css">
    
</head>


   


<body>
    <div class="left-panel">
        <h2>Bienvenido a Nuestra Comunidad</h2>
        <p>Explora, conecta y sé parte de algo increíble. ¡Tu viaje comienza aquí!</p>
        <div id="logo">

        </div>
    </div>
    <div class="right-panel">
    <div class="form-container">
    <a href="./../../index.php" id="volver">< Volver</a>
        <?php if (isset($_GET['exito'])): ?>
            <h2 id="exito">Usuario Creado Con exito</h2>
        <?php endif; ?> 
        <?php if (isset($_GET['usuario'])): ?>
            <h2 class="error">Ha ocurrido un error, consulta al administrador</h2>
        <?php endif; ?>
    <h2>Registro de Usuario</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
        <div>
            <img id="preview" src="../../public/img/usuario.webp" alt="Previsualización de la imagen" style="max-width: 200px; max-height: 200px;border-radius:50%;margin-left:25%;"><br><br>
        </div>

    <!-- Botón de selección de archivo personalizado -->
        <button id="customButton" type="button">Foto De Perfil</button>
        <input type="file" id="imagen" name="imagen" accept="image/*" style="display: none;"><br><br>

        <label for="name">Nombre:</label>
        <input type="text" id="name" class="inputs" name="name" placeholder="Ingresa tu nombre" required>

        <label for="lastName">Apellido:</label>
        <input type="text" id="lastName" class="inputs" name="lastName" placeholder="Ingresa tu apellido" required>
        
        <label for="documento">Documento</label>
        <input type="text" id="documento" class="inputs" name="document" placeholder="Ingresa tu Documento " required>
        
        <label for="telefono">Telefono</label>
        <input type="text" id="telefono" class="inputs" name="phone" placeholder="Ingresa tu Telefono " required>
        
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" class="inputs" placeholder="Ingresa tu correo electrónico" required>
        
        <label for="password">Contraseña:</label>
        <input type="password" id="password" class="inputs" name="password" placeholder="Crea una contraseña" required>

        
        
        

        <div class="terms">
            <input type="checkbox" id="terms" name="terms" required>
            <label for="terms">Acepto los <a href="#">términos y condiciones</a></label>
        </div>
        
        <button type="submit">Registrarse</button>
    </form>
</div>

    </div>
</body>

    <script>
          document.getElementById("customButton").addEventListener("click", function () {
              document.getElementById("imagen").click(); // Simula un clic en el input file
              document.getElementById("imagen").addEventListener("change", function (event) {
        console.log(event.target.files[0]); // Muestra información del archivo seleccionado
        });

          });
      
          document.getElementById("imagen").addEventListener("change", function (event) {
              const file = event.target.files[0];
              const preview = document.getElementById("preview");

              if (file) {
                  const reader = new FileReader();
                  reader.onload = function (e) {
                      preview.src = e.target.result; // Actualiza la previsualización
                  };
                  reader.readAsDataURL(file);
              } else {
                  preview.src = "ruta/de/tu/imagen-predeterminada.jpg"; // Reestablece la imagen por defecto si no hay archivo
              }
          });
    </script>

<?php

$config=include '../../data/config.php';

try{
    $dsn = "mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"];
    $conexion = new PDO($dsn, $config["db"]["user"], $config["db"]["pass"], $config["db"]["options"]);
} catch(PDOException $error){
    $error = $error -> getMessage();
}

if(isset($_POST['name']) && isset($_POST['lastName'])&& isset($_POST['document']) && isset($_POST['phone']) && isset($_POST['email']) && isset($_POST['password']) ){


    $name = $_POST['name'];
    $lastName = $_POST['lastName'];
    $document = $_POST['document'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];


    

    if(!empty($_FILES['imagen']['name'])) {
        $archivo_nombre = time() . "_" . basename($_FILES['imagen']['name']);
        $directorio = __DIR__ . "/../../public/img/uploads/"; // Cambia la ruta según tu estructura.

        // Crear la carpeta si no existe
        if (!is_dir($directorio)) {
            if (!mkdir($directorio, 0777, true)) {
                throw new Exception("No se pudo crear el directorio.");
            }
        }

        $archivo_destino = $directorio . $archivo_nombre;

        // Mover archivo y guardar la ruta relativa
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $archivo_destino)) {
            $archivo_path = "uploads/" . $archivo_nombre;

            // Guarda la ruta en la base de datos o úsala como prefieras
            $userImageUrl = htmlspecialchars($archivo_path);
        } else {
            throw new Exception("Error al subir la imagen.");
        }

        try {
        // Preparar la consulta para evitar inyección SQL
            $stmt = $conexion->prepare('INSERT INTO persona (Nombre, Apellido, Documento, Telefono, Correo, IdRol, Contrasena,archivo_nombre,archivo_path) VALUES (:nombre, :apellido, :documento, :telefono, :correo, 9, :contrasena,:archivo,:ruta)');
            $stmt->bindParam(':nombre', $name, PDO::PARAM_STR);
            $stmt->bindParam(':apellido', $lastName, PDO::PARAM_STR);
            $stmt->bindParam(':documento', $document, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $phone, PDO::PARAM_STR);
            $stmt->bindParam(':correo', $email, PDO::PARAM_STR);
            $stmt->bindParam(':contrasena', $password, PDO::PARAM_STR);
            $stmt->bindParam(':archivo', $archivo_nombre, PDO::PARAM_STR);
            $stmt->bindParam(':ruta', $archivo_path, PDO::PARAM_STR);
            $stmt->execute();

            header('location: ?exito');
        

        }catch (PDOException $e){
        
            exit('Error en la consulta: ' . $e->getMessage());

    
        }
    }else{
        echo"ocurrio un error";
}
}
?>





</html>
