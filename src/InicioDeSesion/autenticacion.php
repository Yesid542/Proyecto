<?php

require '../../vendor/autoload.php';
use \Firebase\JWT\JWT;

$secret_key = "81283741731";
$algorithm = 'HS256';
$issuer = "/src/InicioDeSesion/autenticacion.php";
$issued_at = time();
$expiration_time = $issued_at + (60 * 60); // Token válido por 1 hora
session_start(); // Inicia una nueva sesión o reanuda la existente

// Credenciales de acceso a la base de datos
$config = include './../../data/config.php';

try{
    $dsn = "mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"];
    $conexion = new PDO($dsn, $config["db"]["user"], $config["db"]["pass"], $config["db"]["options"]);
} catch(PDOException $error){
    $error = $error -> getMessage();
}

// Verificar si se han enviado los datos del formulario
if (!isset($_POST['email'], $_POST['contrasena'])) {
    // Redirigir si no se envió la información esperada
    header('Location: ./../index.php');
    exit();
}

$username = $_POST['email'];
$password = $_POST['contrasena'];

try {
    // Preparar la consulta para evitar inyección SQL
    $stmt = $conexion->prepare('SELECT IdPersona, Nombre, Apellido, contrasena, IdRol FROM persona WHERE correo = :username');
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    // Verificar si se encontró el usuario
    if ($stmt->rowCount() > 0) {
        // Recuperar el ID y la contraseña almacenada
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Comparar la contraseña ingresada con la almacenada (sin hash en este caso)
        if ($password === $user['contrasena']) {
            // La conexión es exitosa, iniciar sesión
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $username;
            $_SESSION['id'] = $user['IdPersona'];

            $payload = array(
                "iss" => $issuer,
                "iat" => $issued_at,
                "exp" => $expiration_time,
                "data" => array(
                    "user_id" => $user['IdPersona'],
                    "username" => $user['Nombre']." ".$user['Apellido']
                )
            );

            $jwt = JWT::encode($payload, $secret_key, $algorithm);
            $response = json_encode(array("token" => $jwt));
            $encoded_response = urlencode($response);
            
            header('Location: ./../../index.php?data='.$encoded_response);
            
            exit();
        } else {
            // Contraseña incorrecta
            header('Location: ./login.php?contrasena');
            exit();
        }   
    } else {
        // Usuario no encontrado
        header('Location: ./login.php?usuario');
        exit();
    }
} catch (PDOException $e) {
    // Manejo de errores en caso de fallas en la consulta
    exit('Error en la consulta: ' . $e->getMessage());
}
?>