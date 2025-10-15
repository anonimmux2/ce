<?php
include('includes/conexion.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();

        // Verificar si la contraseña está hasheada o en texto plano
        $hash_db = $fila['password'];
        $es_valida = false;

        // Caso 1: hash bcrypt ($2y$)
        if (substr($hash_db, 0, 4) === '$2y$') {
            $es_valida = password_verify($password, $hash_db);
        } 
        // Caso 2: texto plano (por compatibilidad)
        else {
            $es_valida = ($password === $hash_db);
        }

        if ($es_valida) {
            $_SESSION['usuario'] = $fila['usuario'];
            $_SESSION['rol'] = $fila['rol'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>

<?php include('includes/header.php'); ?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <h3 class="text-center mb-3">Iniciar Sesión</h3>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" class="p-4 bg-white rounded shadow-sm">
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>
