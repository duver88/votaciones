<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identificacion = $_POST["identificacion"];

    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "sistema_votacion");

    // Verificar si el usuario existe y obtener su clase
    $query = "SELECT id, clase, ha_votado FROM usuarios WHERE identificacion = '$identificacion'";
    $resultado = $conexion->query($query);

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        $usuario_id = $usuario['id'];
        $clase = $usuario['clase'];
        $ha_votado = $usuario['ha_votado'];

        // Verificar si el usuario ya ha votado
        if ($ha_votado) {
            echo "Ya has votado.";
            exit;
        }

        // Redirigir según la clase del usuario
        switch ($clase) {
            case 1:
                header("Location: votacion_clase1.php?usuario_id=$usuario_id");
                break;
            case 2:
                header("Location: votacion_clase2.php?usuario_id=$usuario_id");
                break;
            // Agregar más casos según las clases necesarias
            default:
                echo "Clase no válida.";
                break;
        }
    } else {
        echo "Usuario no encontrado.";
    }

    $conexion->close();
}
?>
