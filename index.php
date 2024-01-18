<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['nombres']) || empty($_SESSION['nombres'])) {
    // Redirigir a la página de inicio de sesión si el usuario no está autenticado
    header("Location: login.php");
    exit();
}

// Obtener datos del usuario desde la sesión
$nombres = isset($_SESSION['nombres']) ? $_SESSION['nombres'] : '';
$apellidos = isset($_SESSION['apellidos']) ? $_SESSION['apellidos'] : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <!-- ... (tu código existente) ... -->
</head>
<body>
    <?php include 'templates/header.php'; ?>

    <div class="container">
        <h2>Bienvenido, <?php echo $nombres . ' ' . $apellidos; ?></h2>

        <!-- Contenido de la página principal -->

    </div>

    <?php include 'templates/footer.php'; ?>
</body>
</html>
