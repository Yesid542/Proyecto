<?php
$config=include '../../data/config.php'; // Asegúrate de tener conexión a la base de datos

    try{
        $dsn = "mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"];
        $conexion = new PDO($dsn, $config["db"]["user"], $config["db"]["pass"], $config["db"]["options"]);
    } catch(PDOException $error){
        $error = $error -> getMessage();
    }

    if (isset($_GET['IdCalzado'])) {
        $idCalzado = intval($_GET['IdCalzado']);
        
        // Cambiar el estado de destacado
        $query = $conexion->prepare("UPDATE calzado SET destacado = NOT destacado WHERE IdCalzado = ?");
        $query->execute([$idCalzado]);
        
        // Redirigir a la página anterior
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
    
?>





