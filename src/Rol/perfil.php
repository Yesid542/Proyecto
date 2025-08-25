<?php
session_start();



if (isset($_SESSION['id'])) {
    $usuario = $_SESSION['id'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imageUrl = $_POST["imageUrl"]; // Obtener la URL de la imagen del formulario
    $userImageUrl = $imageUrl; // Guardar la URL de la imagen en una variable PHP

    // Guardar la URL en una sesión para que persista entre actualizaciones de página
    $_SESSION['userImageUrl'] = $userImageUrl;


    // Redirigir de vuelta a la página del perfil con la URL de la imagen
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

$config=include('../../data/config.php');
    try{
        $dsn = "mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"];
        $conexion = new PDO($dsn, $config["db"]["user"], $config["db"]["pass"], $config["db"]["options"]);
    } catch(PDOException $error){
        $error = $error -> getMessage();
    }
    
    try{
        $stmt = $conexion->prepare('SELECT  Nombre, Apellido, Telefono,Correo , Documento,archivo_nombre,archivo_path FROM persona WHERE IdPersona = :usuario');
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();
    
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $imagenPath = "../../public/img/" . $user['archivo_path'];
    
}catch(PDOException $e) {
    // Manejo de errores en caso de fallas en la consulta
    exit('Error en la consulta: ' . $e->getMessage());
}


include('./../templates/header.php');
include('./../templates/navegador.php');?>



    <div class="container">
            <div class="profile-header" style="background-color: #454647;opacity:0.8;border-radius:20px;padding-top:40px;padding-bottom:5px;">
                 <form id="formularioImagen" action="../editar.php" method="POST" enctype="multipart/form-data">
                    <img id="user-image" src="<?= $imagenPath ?>" alt="Foto de Perfil" class="img-preview">
                    <input type="file" id="imagen" name="imagen" accept="image/*" onchange="previewImage(event); enviarFormulario();" style="display: none;">
                    <input type="hidden" name="usuario">
                </form>
                <br>
                <label for="image-url"></label>
                    <button class="btn bg-white mt-3" onclick="document.getElementById('imagen').click()">Cambiar Foto de Perfil</button>
                <br>
                <div id="profileInfo">
                    <h1 class="color"><span id="name"><?php echo $user['Nombre'] ?></span></h1>
                    <p class="datos">Correo: <span id="email"><?php echo $user['Correo'] ?></span></p>
                    <p class="datos">Documento: <span id="document"><?php echo $user['Documento'] ?></span></p>
                    <p class="datos">Teléfono: <span id="phone"><?php echo $user['Telefono'] ?></span></p>
                </div>
                <div class="profile-info">
                    <a class="btn btn-primary" onclick="toggleEditSave()" id="editButton">Editar Perfil</a>
                    <a href="/../src/InicioDeSesion/cerrar_sesion.php" class="btn btn-danger">Cerrar Sesión</a>
                </div>
            </div>
    </div>

<style>
    .profile-header {
        text-align: center;
        margin-top: 40px;
    }
    .profile-pic {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #961B71;
    }
    .profile-info {
        margin-top: 20px;
        margin-bottom: 80px;
    }
    .datos {
        color: white;
    }
</style>
<script>
function toggleEditSave() {
    const isEditing = document.getElementById("editButton").textContent === "Editar Perfil";
    const profileInfo = document.getElementById("profileInfo");

    if (isEditing) {
        // Convert to input fields for editing
        profileInfo.innerHTML = `
            <p class="datos" style="margin-top:10px;">Nombre: <input type="text" id="name-input" value="${document.getElementById('name').textContent}"></p>
            <p class="datos">Correo: <input type="text" id="email-input" value="${document.getElementById('email').textContent}"></p>
            <p class="datos">Documento: <input type="text" id="document-input" value="${document.getElementById('document').textContent}"></p>
            <p class="datos">Teléfono: <input type="text" id="phone-input" value="${document.getElementById('phone').textContent}"></p>
        `;

        document.getElementById("editButton").textContent = "Guardar Perfil";
    } else {
        const nameValue = document.getElementById("name-input").value;
        const emailValue = document.getElementById("email-input").value;
        const documentValue = document.getElementById("document-input").value;
        const phoneValue = document.getElementById("phone-input").value;

        window.location.href = `../editar.php?accion=persona&name=${encodeURIComponent(nameValue)}&email=${encodeURIComponent(emailValue)}&document=${encodeURIComponent(documentValue)}&phone=${encodeURIComponent(phoneValue)}`;
    
        }}
        
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
            const output = document.getElementById('user-image');
            output.src = reader.result; // Previsualizar imagen
        };
        reader.readAsDataURL(event.target.files[0]);

    // Enviar el formulario automáticamente
            enviarFormulario();
        }


        function enviarFormulario() {
            document.getElementById('formularioImagen').submit(); // Enviar el formulario automáticamente.
        }

        window.onload = function() {
            const storedImageUrl = localStorage.getItem('userImage');
            if (storedImageUrl) {
                document.getElementById('user-image').src = storedImageUrl; // Mostrar la imagen desde localStorage
            }
        };

        function saveImage() {
            const imageUrl = document.getElementById('hidden-image-url').value;
            document.getElementById('user-image').src = imageUrl; // Actualizar la imagen
            localStorage.setItem('userImage', imageUrl); // Guardar en localStorage
        }
    </script>

<?php
} else {
    echo "Error: La variable de sesión 'idPersona' no está definida.";
};
?>


<?php include('../templates/footer.php'); ?>