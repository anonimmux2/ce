<?php
include('includes/conexion.php');
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Obtener alumnos y materias
$alumnos = $conexion->query("SELECT id, nombre, apellidos FROM alumnos ORDER BY nombre");
$materias = $conexion->query("SELECT id, nombre FROM materias ORDER BY nombre");

// Procesar formulario
if (isset($_POST['guardar'])) {
    $id_alumno = intval($_POST['alumno']);
    $id_materia = intval($_POST['materia']);
    $fecha = $conexion->real_escape_string($_POST['fecha']);

    $conexion->query("INSERT INTO inscripciones (id_alumno, id_materia, fecha) VALUES ($id_alumno, $id_materia, '$fecha')");
    header("Location: inscripciones.php?msg=agregado");
    exit();
}
?>

<?php include('includes/header.php'); ?>

<h2 class="mb-4">Agregar Nueva Inscripción</h2>

<form method="post" class="w-50 mx-auto">
    <div class="mb-3">
        <label for="alumno" class="form-label">Alumno</label>
        <select name="alumno" id="alumno" class="form-select" required>
            <option value="">-- Seleccionar Alumno --</option>
            <?php while ($fila = $alumnos->fetch_assoc()): ?>
                <option value="<?= $fila['id'] ?>"><?= htmlspecialchars($fila['nombre'] . ' ' . $fila['apellidos']) ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="materia" class="form-label">Materia</label>
        <select name="materia" id="materia" class="form-select" required>
            <option value="">-- Seleccionar Materia --</option>
            <?php while ($fila = $materias->fetch_assoc()): ?>
                <option value="<?= $fila['id'] ?>"><?= htmlspecialchars($fila['nombre']) ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="fecha" class="form-label">Fecha</label>
        <input type="date" name="fecha" id="fecha" class="form-control" required>
    </div>
    <button type="submit" name="guardar" class="btn btn-success">Guardar</button>
    <a href="inscripciones.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include('includes/footer.php'); ?>
