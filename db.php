<?php
// db.php
$host = 'dpg-d4jnrlvdiees73b1dno0-a.oregon-postgres.render.com';
$dbname = 'rasket_demo'; 
$user = 'rasket_demo_user';
$pass = 'JO4l0UouY4A3G4xeutU7zsLDijUWjR78';
$port = '5432'; // El puerto por defecto de Postgres

try {
    // CAMBIO IMPORTANTE: Usar pgsql: y quitar charset del string (se configura después o por defecto)
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $pass);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>