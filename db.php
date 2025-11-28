<?php
// db.php
$host = 'dpg-d4jnrlvdiees73b1dno0-a.oregon-postgres.render.com';
$dbname = 'rasket_demo'; 
$user = 'rasket_demo_user';
$pass = 'JO4l0UouY4A3G4xeutU7zsLDijUWjR78';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>