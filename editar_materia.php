<?php
include('includes/conexion.php');
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Obtener id de la materia
if (!isset($_GET['id'])) {
    header("Location: materias.php");
    exit();
}

$id = intval($_GET['id']);
$result = $conexion->query("SELECT * FROM materias WHERE id = $id");

if ($result->num_rows == 0) {
    header("Location: materias.php");
    exit();
}

$materia = $result->fetch_assoc();

// Procesar formulario
if (isset($_POST['guardar'])) {
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $descripcion = $conexion->real_escape_string($_POST['descripcion']);

    $conexion->query("UPDATE materias SET nombre='$nombre', descripcion='$descripcion' WHERE id=$id");
    header("Location: materias.php?msg=editado");
    exit();
}
?>

<?php include('includes/header.php'); ?>

<h2 class="mb-4">Editar Materia</h2>

<form method="post" class="w-50 mx-auto">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre de la Materia</label>
        <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($materia['nombre']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea name="descripcion" id="descripcion" class="form-control" rows="3"><?= htmlspecialchars($materia['descripcion']) ?></textarea>
    </div>
    <button type="submit" name="guardar" class="btn btn-success">Actualizar</button>
    <a href="materias.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include('includes/footer.php'); ?>
