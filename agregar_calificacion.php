<?php
include('includes/conexion.php');
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Obtener inscripciones que **NO tengan calificación aún**
$inscripciones = $conexion->query("
    SELECT i.id, a.nombre, a.apellidos, m.nombre AS materia
    FROM inscripciones i
    INNER JOIN alumnos a ON i.id_alumno = a.id
    INNER JOIN materias m ON i.id_materia = m.id
    WHERE i.id NOT IN (SELECT id_inscripcion FROM calificaciones)
    ORDER BY i.id DESC
");

// Procesar formulario
if (isset($_POST['guardar'])) {
    $id_inscripcion = intval($_POST['inscripcion']);
    $calificacion = floatval($_POST['calificacion']);
    $fecha = $conexion->real_escape_string($_POST['fecha']);

    // Validar nuevamente en backend (por seguridad)
    $check = $conexion->query("SELECT id FROM calificaciones WHERE id_inscripcion = $id_inscripcion");
    if ($check->num_rows > 0) {
        echo '<div class="alert alert-danger text-center">❌ Ya existe una calificación registrada para esta materia y alumno.</div>';
    } else {
        $conexion->query("INSERT INTO calificaciones (id_inscripcion, calificacion, fecha_registro)
                          VALUES ($id_inscripcion, $calificacion, '$fecha')");
        header("Location: calificaciones.php?msg=agregado");
        exit();
    }
}
?>

<?php include('includes/header.php'); ?>

<h2 class="mb-4">Registrar Calificación</h2>

<form method="post" class="w-50 mx-auto">
    <div class="mb-3">
        <label for="inscripcion" class="form-label">Inscripción</label>
        <select name="inscripcion" id="inscripcion" class="form-select" required>
            <option value="">-- Seleccionar Inscripción --</option>
            <?php while ($fila = $inscripciones->fetch_assoc()): ?>
                <option value="<?= $fila['id'] ?>">
                    <?= htmlspecialchars($fila['nombre'] . ' ' . $fila['apellidos'] . ' - ' . $fila['materia']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="calificacion" class="form-label">Calificación</label>
        <input type="number" step="0.01" name="calificacion" id="calificacion" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="fecha" class="form-label">Fecha de Registro</label>
        <input type="date" name="fecha" id="fecha" class="form-control" required>
    </div>
    <button type="submit" name="guardar" class="btn btn-success">Guardar</button>
    <a href="calificaciones.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include('includes/footer.php'); ?>
