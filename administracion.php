<?php
include('includes/conexion.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Si se confirma eliminación
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("DELETE FROM usuarios WHERE id = $id");
    header("Location: administracion.php?msg=eliminado");
    exit();
}

// Obtener lista de administradores
$result = $conexion->query("SELECT * FROM usuarios WHERE rol = 'admin' ORDER BY id DESC");
?>

<?php include('includes/header.php'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Administradores</h2>
    <a href="agregar_administrador.php" class="btn btn-success">+ Agregar Administrador</a>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'eliminado'): ?>
<div class="alert alert-success text-center">✅ Administrador eliminado correctamente.</div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] == 'agregado'): ?>
<div class="alert alert-success text-center">✅ Administrador agregado correctamente.</div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] == 'editado'): ?>
<div class="alert alert-info text-center">✏️ Administrador actualizado correctamente.</div>
<?php endif; ?>

<div class="table-responsive">
<table class="table table-striped align-middle text-center">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Fecha Registro</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($admin = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $admin['id'] ?></td>
                <td><?= htmlspecialchars($admin['nombre']) ?></td>
                <td><?= htmlspecialchars($admin['usuario']) ?></td>
                <td><?= htmlspecialchars($admin['rol']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($admin['fecha_registro'])) ?></td>
                <td>
                    <a href="editar_administrador.php?id=<?= $admin['id'] ?>" class="btn btn-warning btn-sm me-1">
                        ✏️ Editar
                    </a>
                    <button class="btn btn-danger btn-sm" onclick="abrirModal(<?= $admin['id'] ?>)">
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
        ¿Estás seguro de que deseas eliminar este administrador?
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
    boton.href = "administracion.php?eliminar=" + id;
    var modal = new bootstrap.Modal(document.getElementById('eliminarModal'));
    modal.show();
}
</script>

<?php include('includes/footer.php'); ?>
