<?php
include('includes/conexion.php');
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: alumnos.php");
    exit();
}

$id = intval($_GET['id']);
$result = $conexion->query("SELECT * FROM alumnos WHERE id = $id");
$alumno = $result->fetch_assoc();

if (!$alumno) {
    header("Location: alumnos.php");
    exit();
}

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    $stmt = $conexion->prepare("UPDATE alumnos SET nombre=?, apellidos=?, correo=?, telefono=? WHERE id=?");
    $stmt->bind_param("ssssi", $nombre, $apellidos, $correo, $telefono, $id);

    if ($stmt->execute()) {
        header("Location: alumnos.php?msg=editado");
        exit();
    } else {
        $mensaje = "❌ Error al actualizar el alumno.";
    }
}
?>

<?php include('includes/header.php'); ?>

<h2 class="mb-4">Editar Alumno</h2>

<?php if ($mensaje): ?>
<div class="alert alert-danger text-center"><?= $mensaje ?></div>
<?php endif; ?>

<form method="POST" class="p-4 bg-white rounded shadow-sm">
    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($alumno['nombre']) ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Apellidos</label>
        <input type="text" name="apellidos" value="<?= htmlspecialchars($alumno['apellidos']) ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Correo</label>
        <input type="email" name="correo" value="<?= htmlspecialchars($alumno['correo']) ?>" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Teléfono</label>
        <input type="text" name="telefono" value="<?= htmlspecialchars($alumno['telefono']) ?>" class="form-control">
    </div>

    <div class="d-flex justify-content-between">
        <a href="alumnos.php" class="btn btn-secondary">← Cancelar</a>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </div>
</form>

<?php include('includes/footer.php'); ?>
