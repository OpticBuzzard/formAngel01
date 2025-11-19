<?php
session_start();
require 'db.php';

// Redirigir si ya hay sesión
if (isset($_SESSION['user_id'])) {
    header("Location: dispositivos.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password']; // En producción usa password_verify()

    // Aquí deberías validar contra tu tabla de usuarios real
    // Ejemplo simple simulado:
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email"); 
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user /* && password_verify($password, $user['password']) */) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['flash_message'] = "¡Bienvenido al sistema! Has iniciado sesión correctamente.";
        header("Location: dispositivos.php");
        exit;
    } else {
        $error = "Credenciales inválidas";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f3f4f6; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card-login { width: 100%; max-width: 400px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="card card-login p-4">
        <h3 class="text-center mb-4">Iniciar Sesión</h3>
        <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
        
        <form method="POST" id="loginForm">
            <div class="mb-3">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="email" id="email" class="form-control" required>
                <div class="invalid-feedback">Correo inválido</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
        </form>
    </div>
    <script>
        // Validación JS visual rápida
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                e.preventDefault();
                email.classList.add('is-invalid');
            }
        });
    </script>
</body>
</html>