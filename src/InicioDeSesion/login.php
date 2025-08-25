<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
    <link rel="stylesheet" href="../../public/css/login.css">
    <title>Inicia Sesion</title>
    <meta http-equiv="Content-Security-Policy" content="frame-ancestors 'self' https://www.facebook.com">
</head>
<body>
    <div id="superior">
        <a href="./../../index.php" id="volver">< Volver</a>
        <?php if (isset($_GET['contrasena'])): ?>
            <h2 class="error">Contraseña Incorrecta</h2>
        <?php endif; ?> 
        <?php if (isset($_GET['usuario'])): ?>
            <h2 class="error">Usuario no encontrado</h2>
        <?php endif; ?>
        <?php if (isset($_GET['exito'])): ?>
            <h2 class="success">Contraseña actualizada exitosamente</h2>
        <?php endif; ?>
    </div>
    <div id="agua">
        <div id="logo" style="background-image:url(../../public/img/logo.png);"></div>
        <div id="titulo">Inicia Sesión</div>  
        <form id="formulario" action="./autenticacion.php" method="post">
            <label for="" id="label">Email</label>
            <input type="text" name="email" class="input">
            <label for="" id="label">Contraseña</label>
            <input type="password" name="contrasena" id="" class="input">
            <a href="./recuperar.php" id="recordar">¿Olvidaste tu contraseña?</a>
            <a href="../Rol/registrar.php" id="recordar">Registrate</a>
            <div id="botones" style="display:flex">
                <input type="submit" value="Ingresa" id="ingresar">
                <div id="g_id_onload"
                    data-client_id="TU_CLIENT_ID"
                    data-login_uri="TU_URI_DE_LOGIN"
                    data-auto_select="true"
                    data-itp_support="true">
                </div>
                <div class="g_id_signin"
                    data-type="standard"
                    data-size="large"
                    data-theme="outline"
                    data-text="sign_in_with"
                    data-shape="rectangular"   
                    data-logo_alignment="right">
                </div>
                <div class="fb-login-button" 
                    data-width="" 
                    data-size="large" 
                    data-button-type="login_with" 
                    data-layout="default" 
                    data-auto-logout-link="false" 
                    data-use-continue-as="true">
                </div>
            </div>
        </form>
    </div>
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId      : 'TU_APP_ID',
                cookie     : true,
                xfbml      : true,
                version    : 'v10.0'
            });
            FB.AppEvents.logPageView();
        };

        (function(d, s, id){
           var js, fjs = d.getElementsByTagName(s)[0];
           if (d.getElementById(id)) {return;}
           js = d.createElement(s); js.id = id;
           js.src = "https://connect.facebook.net/en_US/sdk.js";
           fjs.parentNode.insertBefore(js, fjs);
         }(document, 'script', 'facebook-jssdk'));
        
        
        

        function storeToken(jwt) {
            localStorage.setItem('authToken', jwt);
        }

    </script>
    
</body>
</html>
