<?php
include('includes/conexion.php');
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Procesar formulario
if (isset($_POST['guardar'])) {
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $descripcion = $conexion->real_escape_string($_POST['descripcion']);

    $conexion->query("INSERT INTO materias (nombre, descripcion) VALUES ('$nombre', '$descripcion')");
    header("Location: materias.php?msg=agregado");
    exit();
}
?>

<?php include('includes/header.php'); ?>

<h2 class="mb-4">Agregar Nueva Materia</h2>

<form method="post" class="w-50 mx-auto">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre de la Materia</label>
        <input type="text" name="nombre" id="nombre" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea name="descripcion" id="descripcion" class="form-control" rows="3"></textarea>
    </div>
    <button type="submit" name="guardar" class="btn btn-success">Guardar</button>
    <a href="materias.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include('includes/footer.php'); ?>
