<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "INSERT INTO dispositivo (alias, id_tipo_dispositivo, mac_address, numero_serie, modelo, fabricante) 
                VALUES (:alias, :id_tipo, :mac, :serie, :modelo, :fabricante)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':alias' => $_POST['alias'],
            ':id_tipo' => $_POST['id_tipo_dispositivo'],
            ':mac' => $_POST['mac_address'],
            ':serie' => $_POST['numero_serie'],
            ':modelo' => $_POST['modelo'],
            ':fabricante' => $_POST['fabricante']
        ]);

        $_SESSION['flash_message'] = "Dispositivo creado correctamente.";
        $_SESSION['flash_type'] = "success";

    } catch (Exception $e) {
        $_SESSION['flash_message'] = "Error al crear: " . $e->getMessage();
        $_SESSION['flash_type'] = "danger";
    }

    header("Location: dispositivos.php");
    exit;
}