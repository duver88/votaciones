<?php
// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $usuario_id = $_POST['usuario_id'];
    $candidato_id = $_POST['candidato']; // Este es el ID del candidato seleccionado

    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "sistema_votacion");

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Preparar la consulta SQL para insertar el voto
    $query = "INSERT INTO votos (usuario_id, candidato_id) VALUES ('$usuario_id', '$candidato_id')";

    // Ejecutar la consulta
    if ($conexion->query($query) === TRUE) {
        // Actualizar el estado de votación del usuario a "ha_votado = 1"
        $update_query = "UPDATE usuarios SET ha_votado = 1 WHERE id = '$usuario_id'";

        // Ejecutar la actualización del estado del usuario
        if ($conexion->query($update_query) === TRUE) {
            // Redirigir a una página de confirmación u otra ubicación
            header("Location: index.php?voto=exitoso");
            exit;
        } else {
            echo "Error al actualizar el estado del usuario: " . $conexion->error;
        }
    } else {
        echo "Error al procesar el voto: " . $conexion->error;
    }

    // Cerrar la conexión a la base de datos
    $conexion->close();
} else {
    // Si no se envió el formulario por método POST, redirigir a una página de error
    header("Location: error.php");
    exit;
}
?>
