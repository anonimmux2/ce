<?php
include('includes/conexion.php');
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Obtener id de la inscripción
if (!isset($_GET['id'])) {
    header("Location: inscripciones.php");
    exit();
}

$id = intval($_GET['id']);
$result = $conexion->query("SELECT * FROM inscripciones WHERE id = $id");
if ($result->num_rows == 0) {
    header("Location: inscripciones.php");
    exit();
}
$inscripcion = $result->fetch_assoc();

// Obtener alumnos y materias
$alumnos = $conexion->query("SELECT id, nombre, apellidos FROM alumnos ORDER BY nombre");
$materias = $conexion->query("SELECT id, nombre FROM materias ORDER BY nombre");

// Procesar formulario
if (isset($_POST['guardar'])) {
    $id_alumno = intval($_POST['alumno']);
    $id_materia = intval($_POST['materia']);
    $fecha = $conexion->real_escape_string($_POST['fecha']);

    $conexion->query("UPDATE inscripciones SET id_alumno=$id_alumno, id_materia=$id_materia, fecha='$fecha' WHERE id=$id");
    header("Location: inscripciones.php?msg=editado");
    exit();
}
?>

<?php include('includes/header.php'); ?>

<h2 class="mb-4">Editar Inscripción</h2>

<form method="post" class="w-50 mx-auto">
    <div class="mb-3">
        <label for="alumno" class="form-label">Alumno</label>
        <select name="alumno" id="alumno" class="form-select" required>
            <option value="">-- Seleccionar Alumno --</option>
            <?php while ($fila = $alumnos->fetch_assoc()): ?>
                <option value="<?= $fila['id'] ?>" <?= ($fila['id'] == $inscripcion['id_alumno']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($fila['nombre'] . ' ' . $fila['apellidos']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="materia" class="form-label">Materia</label>
        <select name="materia" id="materia" class="form-select" required>
            <option value="">-- Seleccionar Materia --</option>
            <?php while ($fila = $materias->fetch_assoc()): ?>
                <option value="<?= $fila['id'] ?>" <?= ($fila['id'] == $inscripcion['id_materia']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($fila['nombre']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="fecha" class="form-label">Fecha</label>
        <input type="date" name="fecha" id="fecha" class="form-control" value="<?= $inscripcion['fecha'] ?>" required>
    </div>
    <button type="submit" name="guardar" class="btn btn-success">Actualizar</button>
    <a href="inscripciones.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include('includes/footer.php'); ?>
