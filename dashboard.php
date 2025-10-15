<?php
include('includes/conexion.php');
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>

<?php include('includes/header.php'); ?>

<h2 class="mb-4 text-center">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?> 👋</h2>

<div class="row g-4 text-center">

    <div class="col-12 col-md-3">
        <a href="alumnos.php" class="text-decoration-none">
            <div class="card hover-scale p-4">
                <img src="assets/img/Alumnos.png" alt="Alumnos" class="img-fluid mx-auto mb-3 dashboard-icon">
                <h5>Alumnos</h5>
                <p class="text-muted">Gestión de estudiantes</p>
            </div>
        </a>
    </div>

    <div class="col-12 col-md-3">
        <a href="materias.php" class="text-decoration-none">
            <div class="card hover-scale p-4">
                <img src="assets/img/Materias.png" alt="Materias" class="img-fluid mx-auto mb-3 dashboard-icon">
                <h5>Materias</h5>
                <p class="text-muted">Administración de cursos</p>
            </div>
        </a>
    </div>

    <div class="col-12 col-md-3">
        <a href="inscripciones.php" class="text-decoration-none">
            <div class="card hover-scale p-4">
                <img src="assets/img/Inscripciones.png" alt="Inscripciones" class="img-fluid mx-auto mb-3 dashboard-icon">
                <h5>Inscripciones</h5>
                <p class="text-muted">Registro alumno-materia</p>
            </div>
        </a>
    </div>

    <div class="col-12 col-md-3">
        <a href="calificaciones.php" class="text-decoration-none">
            <div class="card hover-scale p-4">
                <img src="assets/img/Calificaciones.png" alt="Calificaciones" class="img-fluid mx-auto mb-3 dashboard-icon">
                <h5>Calificaciones</h5>
                <p class="text-muted">Consulta de notas</p>
            </div>
        </a>
    </div>
</div>

<?php include('includes/footer.php'); ?>
