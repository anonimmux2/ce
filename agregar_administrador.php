<?php
include('includes/conexion.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $rol = trim($_POST['rol']);

    if (empty($nombre) || empty($usuario) || empty($password) || empty($rol)) {
        $mensaje = "⚠️ Todos los campos son obligatorios.";
    } else {
        // Verificar si el usuario ya existe
        $check = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = ?");
        $check->bind_param("s", $usuario);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $mensaje = "❌ El nombre de usuario <strong>$usuario</strong> ya está registrado. Intenta con otro.";
        } else {
            // Insertar nuevo administrador
            $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, usuario, password, rol, fecha_registro) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssss", $nombre, $usuario, $password, $rol);

            if ($stmt->execute()) {
                header("Location: administracion.php?msg=agregado");
                exit();
            } else {
                $mensaje = "❌ Error al guardar el administrador.";
            }
        }

        $check->close();
    }
}
?>

<?php include('includes/header.php'); ?>

<h2 class="mb-4">Agregar Nuevo Administrador</h2>

<?php if ($mensaje): ?>
<div class="alert alert-danger text-center"><?= $mensaje ?></div>
<?php endif; ?>

<form method="POST" class="p-4 bg-white rounded shadow-sm">
    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Usuario</label>
        <input type="text" name="usuario" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Contraseña</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Rol</label>
        <select name="rol" class="form-select" required>
            <option value="admin">Administrador</option>
            <option value="editor">Editor</option>
            <option value="invitado">Invitado</option>
        </select>
    </div>

    <div class="d-flex justify-content-between">
        <a href="administracion.php" class="btn btn-secondary">← Cancelar</a>
        <button type="submit" class="btn btn-success">Guardar Administrador</button>
    </div>
</form>

<?php include('includes/footer.php'); ?>
