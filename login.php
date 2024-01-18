<?php

session_start();

// Incluir el archivo de conexión
require_once 'bd.php';

// Verificar si se ha enviado el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Validar el inicio de sesión directamente sin controladores ni modelos
    $stmt = $conn->prepare("SELECT u.nombres, u.apellidos, r.nombre_rol 
                            FROM usuarios u
                            INNER JOIN rol r ON u.id_rol = r.id_rol
                            WHERE u.nombre_usuario = ? AND u.contrasena = ? AND r.nombre_rol = 'Administrador'
                            LIMIT 1");
    $stmt->execute([$usuario, $contrasena]);
    $usuarioEncontrado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuarioEncontrado) {
        // Almacenar solo nombres y apellidos en la sesión
        $_SESSION['nombres'] = $usuarioEncontrado['nombres'];
        $_SESSION['apellidos'] = $usuarioEncontrado['apellidos'];
        $_SESSION['rol'] = $usuarioEncontrado['nombre_rol'];

        // Redirigir a index.php
        ob_clean();
        header("Location: index.php");
        exit();// Asegúrate de que no haya salida después de esta línea
    } else {
        $mensajeError = "Usuario o contraseña incorrectos o no tienes permisos de administrador";
    }


}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="estilos/login.css">
</head>
<body>
    <div class="container">
        <h2>Iniciar Sesión</h2>

        <!-- Formulario de inicio de sesión -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" required>

            <button type="submit">Iniciar Sesión</button>
        </form>

        <?php
        if (isset($mensajeError)) {
            echo "<p style='color: red;'>$mensajeError</p>";
        }
        ?>
    </div>
</body>
</html>
