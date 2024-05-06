<?php
// Función para mostrar un mensaje de alerta
function mostrarMensaje($mensaje, $tipo = 'info') {
    echo '<div class="alert alert-' . $tipo . ' mt-3" role="alert">' . $mensaje . '</div>';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identificacion = $_POST["identificacion"];

    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "x");

    // Verificar si el usuario existe y obtener su clase y estado de voto
    $query = "SELECT id, id_categoria, ha_votado FROM votantes WHERE numero_documento = '$identificacion'";
    $resultado = $conexion->query($query);

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        $usuario_id = $usuario['id'];
        $clase = $usuario['id_categoria'];
        $ha_votado = $usuario['ha_votado'];

        // Verificar si el usuario ya ha votado
        if ($ha_votado) {
            mostrarMensaje("Ya has votado.", 'danger');
        } else {
            // Redirigir según la clase del usuario
            switch ($clase) {
                case 1:
                    header("Location: votacion_clase1.php?usuario_id=$usuario_id");
                    exit; // Importante salir del script después de la redirección
                case 2:
                    header("Location: votacion_clase2.php?usuario_id=$usuario_id");
                    exit; // Importante salir del script después de la redirección
                // Agregar más casos según las clases necesarias
                default:
                    mostrarMensaje("Clase no válida.", 'warning');
                    break;
            }
        }
    } else {
        mostrarMensaje("Usuario no encontrado.", 'warning');
    }

    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Iniciar Sesión</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <br>
    <button id="accesibilityBtn" class="btn btn-secondary accessibility-btn">Accesibilidad</button>

    <div class="container">
    <div class="card text-center align-middle" style="max-width: 400px; margin: 0 auto; margin-top: 10%;">
        <div class="card-header">Iniciar Sesión</div>
        <div class="card-body">
            <!-- Contenido del formulario -->
            <form action="index.php" method="POST">
                <!-- Campos del formulario -->
                <div class="form-group">
                    <label for="identificacion">Identificación:</label>
                    <input type="text" class="form-control" id="identificacion" name="identificacion" required aria-required="true">
                </div>
                <!-- Botón de enviar -->
                <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
            </form>
            <!-- Mostrar mensajes de alerta -->
            <?php
                // Aquí podrías mostrar el mensaje de alerta usando la función `mostrarMensaje`
                // Por ejemplo: mostrarMensaje("Usuario no encontrado.", 'warning');
            ?>
        </div>
    </div>
</div>


    <div id="accesibilityPanel" class="accesibility-panel" style="display: none;">
        <h5>Opciones de accesibilidad</h5>
        <div class="form-group">
            <label for="textSizeSelect">Tamaño de texto:</label>
            <select id="textSizeSelect" class="form-control">
                <option value="16">Pequeño</option>
                <option value="27" selected>Normal</option>
                <option value="30">Grande</option>
                <option value="38">Muy grande</option>
            </select>
        </div>
        <div class="form-group">
            <label for="colorSchemeSelect">Esquema de color:</label>
            <select id="colorSchemeSelect" class="form-control">
                <option value="default" selected>Predeterminado</option>
                <option value="high-contrast">Alto contraste</option>
                <option value="grayscale">Escala de grises</option>
            </select>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
