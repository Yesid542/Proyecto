
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../public/assets/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../public/css/recuperar.css">
    
</head>
<body>
    <div class="container">
    <a href="./../../index.php" style="text-decoration:none;color:purple;">< Volver</a>
    <?php if (isset($_GET['exito'])): ?>
            <h5 style="color: rgb(48, 160, 123);">Correo Enviado Exitosamente</h5>
        <?php endif; ?> 
        <?php if (isset($_GET['falla'])): ?>
            <h5 style="color: rgb(129, 50, 45);">No se pudo enviar el correo, contacta al administrador</h5>
        <?php endif; ?>
        <?php if (isset($_GET['usuario'])): ?>
            <h5 style="color: rgb(129, 50, 45);">El correo no existe en nuestra base de datos</h5>
        <?php endif; ?>
        <div id="agua">
            <h2 class="text-center titulo">Recupera tu contraseña</h2>
            <p id="texto">Te enviaremos un correo con los pasos a seguir</p>
            <form id="reset-form" action="./email.php" method="POST">
                <div class="">
                    <label for="email" id="correo">Ingresa tu Correo Electronico:</label>
                    <input type="email" class="form-control label" id="email" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary w-90 boton">Enviar</button>
            </form>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

   

</body>
</html>
<?php
    include('../templates/footer.php');
    ?>
