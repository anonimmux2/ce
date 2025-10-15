<?php
include('includes/conexion.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$mensaje = "";

// Obtener el ID del administrador a editar
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: administracion.php");
    exit();
}

$id = intval($_GET['id']);

// Obtener datos actuales del administrador
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if (!$admin) {
    header("Location: administracion.php");
    exit();
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $rol = $_POST['rol'];
    $password = $_POST['password'];

    // Validar campos obligatorios
    if (empty($nombre) || empty($usuario) || empty($rol)) {
        $mensaje = "❌ Todos los campos obligatorios deben ser completados.";
    } else {
        if (!empty($password)) {
            // Actualizar también la contraseña
            $stmt = $conexion->prepare("UPDATE usuarios SET nombre=?, usuario=?, rol=?, password=? WHERE id=?");
            $stmt->bind_param("ssssi", $nombre, $usuario, $rol, $password, $id);
        } else {
            // Actualizar sin cambiar la contraseña
            $stmt = $conexion->prepare("UPDATE usuarios SET nombre=?, usuario=?, rol=? WHERE id=?");
            $stmt->bind_param("sssi", $nombre, $usuario, $rol, $id);
        }

        if ($stmt->execute()) {
            header("Location: administracion.php?msg=editado");
            exit();
        } else {
            $mensaje = "❌ Error al actualizar el administrador.";
        }
    }
}
?>

<?php include('includes/header.php'); ?>

<h2 class="mb-4">Editar Administrador</h2>

<?php if ($mensaje): ?>
<div class="alert alert-danger text-center"><?= $mensaje ?></div>
<?php endif; ?>

<form method="POST" class="p-4 bg-white rounded shadow-sm">
    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" required value="<?= htmlspecialchars($admin['nombre']) ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Usuario</label>
        <input type="text" name="usuario" class="form-control" required value="<?= htmlspecialchars($admin['usuario']) ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Rol</label>
        <select name="rol" class="form-control" required>
            <option value="admin" <?= $admin['rol'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
            <option value="user" <?= $admin['rol'] == 'user' ? 'selected' : '' ?>>Usuario</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Nueva Contraseña (Opcional)</label>
        <input type="password" name="password" class="form-control">
        <small class="form-text text-muted">Dejar en blanco si no deseas cambiarla</small>
    </div>

    <div class="d-flex justify-content-between">
        <a href="administracion.php" class="btn btn-secondary">← Cancelar</a>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
    </div>
</form>

<?php include('includes/footer.php'); ?>

