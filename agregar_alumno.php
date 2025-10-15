<?php
include('includes/conexion.php');
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    $stmt = $conexion->prepare("INSERT INTO alumnos (nombre, apellidos, correo, telefono) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $apellidos, $correo, $telefono);

    if ($stmt->execute()) {
        header("Location: alumnos.php?msg=agregado");
        exit();
    } else {
        $mensaje = "❌ Error al guardar el alumno.";
    }
}
?>

<?php include('includes/header.php'); ?>

<h2 class="mb-4">Agregar Nuevo Alumno</h2>

<?php if ($mensaje): ?>
<div class="alert alert-danger text-center"><?= $mensaje ?></div>
<?php endif; ?>

<form method="POST" class="p-4 bg-white rounded shadow-sm">
    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Apellidos</label>
        <input type="text" name="apellidos" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Correo</label>
        <input type="email" name="correo" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Teléfono</label>
        <input type="text" name="telefono" class="form-control">
    </div>

    <div class="d-flex justify-content-between">
        <a href="alumnos.php" class="btn btn-secondary">← Cancelar</a>
        <button type="submit" class="btn btn-success">Guardar Alumno</button>
    </div>
</form>

<?php include('includes/footer.php'); ?>
