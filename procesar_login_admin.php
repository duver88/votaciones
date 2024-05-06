<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $clave = $_POST["clave"];

    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "sistema_votacion");

    // Verificar conexión
    if ($conexion->connect_error) {
        die("Error en la conexión: " . $conexion->connect_error);
    }

    // Consulta SQL para verificar las credenciales del administrador
    $query = "SELECT id FROM administradores WHERE usuario = '$usuario' AND clave = '$clave'";
    $resultado = $conexion->query($query);

    if ($resultado->num_rows > 0) {
        // Inicio de sesión exitoso
        $_SESSION["admin"] = true;
        header("Location: admin.php"); // Redirigir al panel de administración
    } else {
        // Credenciales incorrectas
        echo "Usuario o contraseña incorrectos. Intenta nuevamente.";
    }

    $conexion->close();
}
?>
