<?php
$config = include '../../data/config.php';

try {
    $dsn = "mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"];
    $conexion = new PDO($dsn, $config["db"]["user"], $config["db"]["pass"], $config["db"]["options"]);
    
    $stmt = $conexion->prepare('SELECT * FROM tipoCalzado');
    $stmt->execute();
    $tiposCalzado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: text/html; charset=UTF-8');
    foreach($tiposCalzado as $tipo) {
        echo '<option>' . htmlspecialchars($tipo['Nombre'], ENT_QUOTES, 'UTF-8') . '</option>';
    }
} catch (PDOException $error) {
    echo '<option>Error al cargar opciones</option>';
}