<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Voto realizado</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #343a40;
            color: #ffffff;
            text-align: center;
            padding-top: 100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>¡Tu voto se ha realizado correctamente!</h2>
                <?php
                // Obtener los IDs del usuario y del candidato desde la URL
                $usuario_id = isset($_GET['usuario_id']) ? $_GET['usuario_id'] : '';
                $candidato_id = isset($_GET['candidato_id']) ? $_GET['candidato_id'] : '';

                // Conexión a la base de datos
                $conexion = new mysqli("localhost", "root", "", "x");

                // Verificar la conexión
                if ($conexion->connect_error) {
                    die("Error de conexión: " . $conexion->connect_error);
                }

                // Consulta SQL para obtener la hora del voto
                $query = "SELECT fecha_hora_registro FROM votos_web WHERE id_votante = ? AND id_candidato = ? ORDER BY fecha_hora_registro DESC LIMIT 1";
                $stmt = $conexion->prepare($query);
                $stmt->bind_param("ii", $usuario_id, $candidato_id);
                $stmt->execute();
                $resultado = $stmt->get_result();

                if ($resultado->num_rows > 0) {
                    $fila = $resultado->fetch_assoc();
                    $hora_voto = $fila['fecha_hora_registro'];
                    echo "<p>Hora del voto: " . htmlspecialchars($hora_voto) . "</p>";
                } else {
                    echo "<p>No se pudo obtener la hora del voto.</p>";
                }

                // Cerrar la conexión y la sentencia preparada
                $stmt->close();
                $conexion->close();
                ?>
                <a href="index.php" class="btn btn-primary">Volver al inicio</a>
            </div>
        </div>
    </div>
</body>
</html>