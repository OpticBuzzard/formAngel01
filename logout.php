<?php
session_start(); // Inicia la sesión para poder acceder a ella

// Limpia todas las variables de sesión
session_unset();

// Destruye la sesión completamente
session_destroy();

// Redirige al usuario al login (index.php)
header("Location: index.php");
exit;
?>