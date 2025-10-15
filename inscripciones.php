<?php
include('includes/conexion.php');
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Eliminar inscripción
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("DELETE FROM inscripciones WHERE id = $id");
    header("Location: inscripciones.php?msg=eliminado");
    exit();
}

$sql = "SELECT i.id, a.nombre AS alumno, a.apellidos, m.nombre AS materia, i.fecha
        FROM inscripciones i
        INNER JOIN alumnos a ON i.id_alumno = a.id
        INNER JOIN materias m ON i.id_materia = m.id
        ORDER BY i.id DESC";
$result = $conexion->query($sql);
?>

<?php include('includes/header.php'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Inscripciones</h2>
    <a href="agregar_inscripcion.php" class="btn btn-success">+ Nueva Inscripción</a>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'eliminado'): ?>
<div class="alert alert-success text-center">✅ Inscripción eliminada correctamente.</div>
<?php endif; ?>

<div class="table-responsive">
<table class="table table-hover align-middle text-center">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Alumno</th>
            <th>Materia</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($fila = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $fila['id'] ?></td>
                <td><?= htmlspecialchars($fila['alumno'] . ' ' . $fila['apellidos']) ?></td>
                <td><?= htmlspecialchars($fila['materia']) ?></td>
                <td><?= $fila['fecha'] ?></td>
                <td>
                    <a href="editar_inscripcion.php?id=<?= $fila['id'] ?>" class="btn btn-warning btn-sm me-1">
                        ✏️ Editar
                    </a>
                    <button class="btn btn-danger btn-sm" onclick="abrirModal(<?= $fila['id'] ?>)">
                        🗑️ Eliminar
                    </button>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</div>

<!-- Modal Bootstrap para eliminar -->
<div class="modal fade" id="eliminarModal" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="eliminarModalLabel">Confirmar Eliminación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas eliminar esta inscripción?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <a href="#" id="confirmarEliminarBtn" class="btn btn-danger">Eliminar</a>
      </div>
    </div>
  </div>
</div>

<script>
// Función para abrir el modal y pasar el id
function abrirModal(id) {
    var boton = document.getElementById('confirmarEliminarBtn');
    boton.href = "inscripciones.php?eliminar=" + id;
    var modal = new bootstrap.Modal(document.getElementById('eliminarModal'));
    modal.show();
}
</script>

<?php include('includes/footer.php'); ?>
