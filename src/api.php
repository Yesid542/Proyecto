<?php

session_start();

require('../vendor/autoload.php');
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

session_start();
// Manejo de solicitudes OPTIONS para CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    exit(0); // Detiene la solicitud aquí
}

// Permitir solicitudes desde cualquier origen
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Verifica que el método sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "status" => "error",
        "message" => "Solo se permite el método POST.",
        "method" => $_SERVER['REQUEST_METHOD']
    ]);
    exit(0);
}

// Procesa la solicitud POST
$jwt = $_POST['token'] ?? null;
$secretKey = "81283741731";

// Verifica si el token fue proporcionado
if ($jwt) {
    // Respuesta de éxito
    try {
        $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
        $decodedArray = (array) $decoded;
    
        // Acceder a la propiedad user_id dentro de data
        $idPersona = $decodedArray['data']->user_id;
        $_SESSION['id']=$idPersona;
        include('perfil.php');
        include('editar.php');
        include('navegador.php');
        include('index.php');
        
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
}}
exit;
?>

