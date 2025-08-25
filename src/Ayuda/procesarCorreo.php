<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST["nombre"]);
    $email = htmlspecialchars($_POST["email"]);
    $telefono = htmlspecialchars($_POST["telefono"]);
    $tipo = htmlspecialchars($_POST["tipo"]);
    $mensaje = htmlspecialchars($_POST["mensaje"]);

    $destinatario = "wiigosport1@gmail.com";
    $asunto = "[$tipo] Nueva Solicitud de $nombre";

    // Cuerpo del mensaje
    $cuerpo = "Nombre: $nombre\n";
    $cuerpo .= "Correo: $email\n";
    $cuerpo .= "Teléfono: $telefono\n";
    $cuerpo .= "Tipo de PQRSF: $tipo\n\n";
    $cuerpo .= "Mensaje:\n$mensaje\n";

    // Cabeceras del correo
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";

    // Manejo de archivo adjunto (si existe)
    if (!empty($_FILES["archivo"]["name"])) {
        $archivo_nombre = $_FILES["archivo"]["name"];
        $archivo_temp = $_FILES["archivo"]["tmp_name"];
        $archivo_tipo = $_FILES["archivo"]["type"];

        $archivo = chunk_split(base64_encode(file_get_contents($archivo_temp)));

        $boundary = md5(uniqid(time()));

        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

        $mensajeCorreo = "--$boundary\r\n";
        $mensajeCorreo .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $mensajeCorreo .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $mensajeCorreo .= $cuerpo . "\r\n\r\n";

        // Adjuntar archivo
        $mensajeCorreo .= "--$boundary\r\n";
        $mensajeCorreo .= "Content-Type: $archivo_tipo; name=\"$archivo_nombre\"\r\n";
        $mensajeCorreo .= "Content-Transfer-Encoding: base64\r\n";
        $mensajeCorreo .= "Content-Disposition: attachment; filename=\"$archivo_nombre\"\r\n\r\n";
        $mensajeCorreo .= $archivo . "\r\n\r\n";
        $mensajeCorreo .= "--$boundary--";

        $cuerpo = $mensajeCorreo;
    }

    // Enviar el correo
    if (mail($destinatario, $asunto, $cuerpo, $headers)) {
        echo "Gracias, $nombre. Tu solicitud ha sido enviada correctamente.";
    } else {
        echo "Error al enviar el correo. Inténtalo de nuevo.";
    }
}
?>
