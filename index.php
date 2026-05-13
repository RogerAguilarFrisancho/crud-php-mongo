<?php
require 'conexion.php';

// --- LÓGICA DE CREACIÓN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_equipo'])) {
    $nuevoEquipo = [
        'nombre' => $_POST['nombre_equipo'],
        'tipo' => $_POST['tipo_dispositivo'],
        'estado' => $_POST['estado'],
        'fecha_registro' => new MongoDB\BSON\UTCDateTime()
    ];
    $coleccion->insertOne($nuevoEquipo);
    header("Location: index.php");
    exit;
}

// --- LÓGICA DE ELIMINACIÓN ---
if (isset($_GET['eliminar'])) {
    $id = new MongoDB\BSON\ObjectId($_GET['eliminar']);
    $coleccion->deleteOne(['_id' => $id]);
    header("Location: index.php");
    exit;
}

$equipos = $coleccion->find();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario Distribuido - UNAMBA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4 text-primary">Inventario de Laboratorio (Sistemas Distribuidos)</h2>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-secondary text-white shadow p-3">
                    <h5>Nuevo Registro</h5>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nombre del Equipo / ID</label>
                            <input type="text" name="nombre_equipo" class="form-control" placeholder="Ej: PC-LAB01-05" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo</label>
                            <select name="tipo_dispositivo" class="form-select">
                                <option value="Laptop">Laptop</option>
                                <option value="Desktop">Desktop</option>
                                <option value="Servidor">Servidor</option>
                                <option value="Router/Switch">Router/Switch</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="Operativo">Operativo ✅</option>
                                <option value="En Mantenimiento">En Mantenimiento 🛠️</option>
                                <option value="Fuera de Servicio">Fuera de Servicio ❌</option>
                            </select>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Registrar Dispositivo</button>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="table-responsive bg-white text-dark rounded shadow p-3">
                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Equipo</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($equipos as $equipo): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($equipo['nombre']); ?></strong></td>
                                <td><?php echo htmlspecialchars($equipo['tipo']); ?></td>
                                <td>
                                    <span class="badge <?php echo $equipo['estado'] == 'Operativo' ? 'bg-success' : ($equipo['estado'] == 'En Mantenimiento' ? 'bg-warning' : 'bg-danger'); ?>">
                                        <?php echo htmlspecialchars($equipo['estado']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="index.php?eliminar=<?php echo $equipo['_id']; ?>" class="btn btn-outline-danger btn-sm">Eliminar</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if (iterator_count($coleccion->find()) === 0): ?>
                        <p class="text-center text-muted">No hay equipos registrados en la base de datos distribuida.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>