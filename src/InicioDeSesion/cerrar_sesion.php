<?php
session_start();  // Inicia una nueva sesión o reanuda la existente.

// Libera todas las variables de sesión
session_unset();  

// Destruye toda la información registrada de una sesión.
session_destroy();  
?>
<script>
// Limpiar todo el localStorage
localStorage.clear();

// Redireccionar después de limpiar el localStorage
window.location.href = './login.php';
</script>
<?php
exit();  // Asegúrate de que el script se detenga aquí.
?>
