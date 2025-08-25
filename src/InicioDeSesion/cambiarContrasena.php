<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../public/css/CambiarContrasena.css">
</head>

<?php

if ($_SERVER["REQUEST_METHOD"] == "GET"){
    $correo=$_GET['correo'];
}

?>

<body>
    <div id="fondo">
        <form id="passwordForm" action="../editar.php" method="post">
          <label for="password">Nueva Contraseña:</label>
          <input type="hidden" name="accion" value="contrasena">
          <input type="hidden" name="correo" value="<?=$correo ?>">
          <input type="password" id="password" name="password" oninput="checkPasswordStrength()" required>
          <ul id="requirements">
            <li id="length" class="invalid">Al menos 8 caracteres</li>
            <li id="uppercase" class="invalid">Una letra mayúscula</li>
            <li id="lowercase" class="invalid">Una letra minúscula</li>
            <li id="number" class="invalid">Un número</li>
            <li id="special" class="invalid">Un carácter especial (e.g. @, $, !)</li>
          </ul>
          <button id="submitButton" type="submit" disabled>Actualizar</button>
        </form>
    </div>

<script>
  function checkPasswordStrength() {
  const password = document.getElementById('password').value;

  const lengthValid = password.length >= 8;
  const uppercaseValid = /[A-Z]/.test(password);
  const lowercaseValid = /[a-z]/.test(password);
  const numberValid = /\d/.test(password);
  const specialValid = /[@$!%*?&]/.test(password);

  // Actualizar estados de la lista
  document.getElementById('length').className = lengthValid ? 'valid' : 'invalid';
  document.getElementById('uppercase').className = uppercaseValid ? 'valid' : 'invalid';
  document.getElementById('lowercase').className = lowercaseValid ? 'valid' : 'invalid';
  document.getElementById('number').className = numberValid ? 'valid' : 'invalid';
  document.getElementById('special').className = specialValid ? 'valid' : 'invalid';

  // Habilitar o deshabilitar el botón
  const allValid = lengthValid && uppercaseValid && lowercaseValid && numberValid && specialValid;
  document.getElementById('submitButton').disabled = !allValid;
}

</script>
    
</body>
</html>




