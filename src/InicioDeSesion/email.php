<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

$correo=$_POST['email'];


$config = include '../../data/config.php';

try{
    $dsn = "mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"];
    $conexion = new PDO($dsn, $config["db"]["user"], $config["db"]["pass"], $config["db"]["options"]);
} catch(PDOException $error){
    $error = $error -> getMessage();
}

$stmt = $conexion->prepare('SELECT IdPersona, Nombre, Apellido, Correo FROM persona WHERE correo = :correo');
    $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($correo==$user['Correo']){
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP de Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'wiigosport1@gmail.com';
            $mail->Password = 'quhz mhgq whhg bmdh'; // Asegúrate de usar una contraseña de aplicación
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
        
            // Remitente y destinatario
            $mail->setFrom('wiigosport1@gmail.com', 'WiigoSport');
            $mail->addAddress($correo, $user['Nombre']);
        
            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Recovery Password';

            $mail->Body = "
<html>
<head>
    <style>
        .button {
            background-color: rgb(106, 176, 216);
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <p>Selecciona el siguiente botón:</p>
    <a href=\"http://localhost/src/InicioDeSesion/cambiarContrasena.php?accion=contrasena&correo=$correo\" class=\"button\">Haz clic aquí</a>
</body>
</html>";

        
            $mail->send();
            header('location: ./recuperar.php?exito');
        } catch (Exception $e) {
            header('location: ./recuperar.php?falla');
        }

    }else{
        header('location: ./recuperar.php?usuario');
    }




?>
