<?php
session_start();
require 'db.php';

if (isset($_GET['id'])) {
    try {
        $id = $_GET['id'];
        
        $stmt = $pdo->prepare("DELETE FROM dispositivo WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $_SESSION['flash_message'] = "Dispositivo eliminado correctamente.";
        $_SESSION['flash_type'] = "warning";

    } catch (Exception $e) {
        $_SESSION['flash_message'] = "Error al eliminar: " . $e->getMessage();
        $_SESSION['flash_type'] = "danger";
    }
}

header("Location: dispositivos.php");
exit;