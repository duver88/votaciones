<?php
// Verificar si se envió el formulario por método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $usuario_id = $_POST['usuario_id'];
    $candidato_id = $_POST['candidato_id'];

    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "x");

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    try {
        // Preparar la consulta SQL utilizando una sentencia preparada
        $query = "INSERT INTO votos_web (id_votante, id_candidato) VALUES (?, ?)";
        $stmt = $conexion->prepare($query);

        // Vincular parámetros y ejecutar la consulta
        $stmt->bind_param("ii", $usuario_id, $candidato_id);
        $stmt->execute();

        // Verificar si se insertó correctamente el voto
        if ($stmt->affected_rows > 0) {
            // Actualizar el estado de votación del usuario a 'ha_votado = 1'
            $update_query = "UPDATE votantes SET ha_votado = 1 WHERE id = ?";
            $stmt_update = $conexion->prepare($update_query);
            $stmt_update->bind_param("i", $usuario_id);
            $stmt_update->execute();

            // Redirigir a la página de confirmación de voto
            header("Location: voto_realizado.php?usuario_id=" . urlencode($usuario_id) . "&candidato_id=" . urlencode($candidato_id));
            exit;
        } else {
            echo "Error al procesar el voto.";
        }

        // Cerrar las sentencias preparadas
        $stmt->close();
        $stmt_update->close();
    } catch (mysqli_sql_exception $e) {
        echo "Error de SQL: " . $e->getMessage();
    }

    // Cerrar la conexión
    $conexion->close();
} else {
    // Si no se envió el formulario por método POST, redirigir a una página de error
    header("Location: error.php");
    exit;
}
?>