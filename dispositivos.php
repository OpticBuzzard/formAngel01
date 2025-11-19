<?php
session_start();
require 'db.php';

// 1. Seguridad
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// 2. Búsqueda
$search = isset($_GET['search']) ? $_GET['search'] : '';
$whereClause = "";
$params = [];

if ($search) {
    $whereClause = "WHERE d.alias LIKE :search OR d.modelo LIKE :search OR d.mac_address LIKE :search";
    $params['search'] = "%$search%";
}

// 3. Consulta SQL Principal
$sql = "SELECT d.*, t.descripcion as tipo_descripcion 
        FROM dispositivo d 
        LEFT JOIN tipo_dispositivo t ON d.id_tipo_dispositivo = t.id 
        $whereClause 
        ORDER BY d.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$dispositivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 4. Consulta de Tipos (Para los selects de los modales)
$tiposStmt = $pdo->query("SELECT * FROM tipo_dispositivo");
$tiposDispositivo = $tiposStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Dispositivos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body class="bg-light p-4">

<div class="container-fluid">
    
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="alert alert-<?= isset($_SESSION['flash_type']) ? $_SESSION['flash_type'] : 'info' ?> alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-2"></i>
            <?= $_SESSION['flash_message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php 
            unset($_SESSION['flash_message']); 
            unset($_SESSION['flash_type']); 
        ?>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
                <form action="" method="GET" class="d-flex align-items-center">
                    <input type="search" name="search" class="form-control" value="<?= htmlspecialchars($search) ?>" placeholder="Buscar dispositivo...">
                    <button type="submit" class="btn btn-light ms-2"><i class="bx bx-search-alt"></i></button>
                </form>
                <div>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="bx bx-plus me-1"></i>Nuevo
                    </button>
                    <a href="logout.php" class="btn btn-outline-danger ms-2">Salir</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Alias</th>
                            <th>Tipo</th>
                            <th>MAC</th>
                            <th>Serie</th>
                            <th>Modelo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($dispositivos) > 0): ?>
                            <?php foreach ($dispositivos as $d): ?>
                                <tr>
                                    <td><?= $d['id'] ?></td>
                                    <td><?= htmlspecialchars($d['alias']) ?></td>
                                    <td><?= $d['tipo_descripcion'] ?></td>
                                    <td><?= $d['mac_address'] ?></td>
                                    <td><?= $d['numero_serie'] ?></td>
                                    <td><?= $d['modelo'] ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary btn-edit" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal"
                                                data-info='<?= json_encode($d) ?>'>
                                            <i class="bx bx-edit"></i>
                                        </button>
                                        
                                        <button class="btn btn-sm btn-danger btn-delete" 
                                                onclick="confirmarEliminar(<?= $d['id'] ?>)">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center">No se encontraron registros.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="guardar_dispositivo.php" method="POST">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Nuevo Dispositivo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <?php include 'form_campos.php'; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="editar_dispositivo.php" method="POST">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Editar Dispositivo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Alias</label>
                                <input type="text" name="alias" id="edit_alias" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Tipo</label>
                                <select name="id_tipo_dispositivo" id="edit_id_tipo" class="form-control" required>
                                    <?php foreach($tiposDispositivo as $t): ?>
                                        <option value="<?= $t['id'] ?>"><?= $t['descripcion'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>MAC Address</label>
                                <input type="text" name="mac_address" id="edit_mac" class="form-control mac-mask">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Nº Serie</label>
                                <input type="text" name="numero_serie" id="edit_serie" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Modelo</label>
                                <input type="text" name="modelo" id="edit_modelo" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Fabricante</label>
                                <input type="text" name="fabricante" id="edit_fabricante" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<?php
// Guarda esto en un archivo form_campos.php o pégalo dentro del modal crear directamente si prefieres
// Aquí lo simulo para que veas el HTML
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // 1. Lógica para Eliminar
    function confirmarEliminar(id) {
        if(confirm('¿Estás seguro de eliminar este dispositivo? Esta acción no se puede deshacer.')) {
            window.location.href = 'eliminar_dispositivo.php?id=' + id;
        }
    }

    // 2. Lógica para Editar (Rellenar Modal)
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            // Obtener el JSON del botón
            const data = JSON.parse(this.dataset.info);

            // Rellenar los inputs del modal Edit
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_alias').value = data.alias;
            document.getElementById('edit_id_tipo').value = data.id_tipo_dispositivo;
            document.getElementById('edit_mac').value = data.mac_address;
            document.getElementById('edit_serie').value = data.numero_serie;
            document.getElementById('edit_modelo').value = data.modelo;
            document.getElementById('edit_fabricante').value = data.fabricante;
        });
    });

    // 3. Máscara MAC Address (Opcional, para mejorar UX)
    document.querySelectorAll('.mac-mask').forEach(input => {
        input.addEventListener('input', function(e) {
            let v = e.target.value.replace(/[^0-9A-Fa-f]/g, '').substring(0,12);
            let mac = v.match(/.{1,2}/g)?.join(':') || v;
            e.target.value = mac.toUpperCase();
        });
    });
</script>

</body>
</html>