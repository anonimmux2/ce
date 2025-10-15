<?php
include('includes/conexion.php');
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Obtener id de la calificación
if (!isset($_GET['id'])) {
    header("Location: calificaciones.php");
    exit();
}

$id = intval($_GET['id']);
$result = $conexion->query("SELECT * FROM calificaciones WHERE id = $id");
if ($result->num_rows == 0) {
    header("Location: calificaciones.php");
    exit();
}
$calificacion = $result->fetch_assoc();

// Obtener inscripciones
$inscripciones = $conexion->query("SELECT i.id, a.nombre, a.apellidos, m.nombre AS materia
                                   FROM inscripciones i
                                   INNER JOIN alumnos a ON i.id_alumno = a.id
                                   INNER JOIN materias m ON i.id_materia = m.id
                                   ORDER BY i.id DESC");

// Procesar formulario
if (isset($_POST['guardar'])) {
    $id_inscripcion = intval($_POST['inscripcion']);
    $calif = floatval($_POST['calificacion']);
    $fecha = $conexion->real_escape_string($_POST['fecha']);

    // Verificar que no exista otra calificación para la misma inscripción
    $check = $conexion->query("SELECT id FROM calificaciones 
                               WHERE id_inscripcion = $id_inscripcion AND id != $id");
    if ($check->num_rows > 0) {
        // Ya existe otra calificación
        echo '<div class="alert alert-danger text-center">❌ Ya existe una calificación para esta materia.</div>';
    } else {
        // Actualizar normalmente
        $conexion->query("UPDATE calificaciones
                          SET id_inscripcion=$id_inscripcion, calificacion=$calif, fecha_registro='$fecha'
                          WHERE id=$id");
        header("Location: calificaciones.php?msg=editado");
        exit();
    }
}
?>

<?php include('includes/header.php'); ?>

<h2 class="mb-4">Editar Calificación</h2>

<form method="post" class="w-50 mx-auto">
    <div class="mb-3">
        <label for="inscripcion" class="form-label">Inscripción</label>
        <select name="inscripcion" id="inscripcion" class="form-select" required>
            <option value="">-- Seleccionar Inscripción --</option>
            <?php while ($fila = $inscripciones->fetch_assoc()): ?>
                <option value="<?= $fila['id'] ?>" <?= ($fila['id'] == $calificacion['id_inscripcion']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($fila['nombre'] . ' ' . $fila['apellidos'] . ' - ' . $fila['materia']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="calificacion" class="form-label">Calificación</label>
        <input type="number" step="0.01" name="calificacion" id="calificacion" class="form-control" value="<?= $calificacion['calificacion'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="fecha" class="form-label">Fecha de Registro</label>
        <input type="date" name="fecha" id="fecha" class="form-control" 
               value="<?= date('Y-m-d', strtotime($calificacion['fecha_registro'])) ?>" required>
    </div>
    <button type="submit" name="guardar" class="btn btn-success">Actualizar</button>
    <a href="calificaciones.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include('includes/footer.php'); ?>
