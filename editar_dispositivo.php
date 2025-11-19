<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id']; // ID oculto en el formulario

        $sql = "UPDATE dispositivo SET 
                alias = :alias,
                id_tipo_dispositivo = :id_tipo,
                mac_address = :mac,
                numero_serie = :serie,
                modelo = :modelo,
                fabricante = :fabricante
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':alias' => $_POST['alias'],
            ':id_tipo' => $_POST['id_tipo_dispositivo'],
            ':mac' => $_POST['mac_address'],
            ':serie' => $_POST['numero_serie'],
            ':modelo' => $_POST['modelo'],
            ':fabricante' => $_POST['fabricante'],
            ':id' => $id
        ]);

        $_SESSION['flash_message'] = "Dispositivo actualizado correctamente.";
        $_SESSION['flash_type'] = "primary";

    } catch (Exception $e) {
        $_SESSION['flash_message'] = "Error al actualizar: " . $e->getMessage();
        $_SESSION['flash_type'] = "danger";
    }

    header("Location: dispositivos.php");
    exit;
}