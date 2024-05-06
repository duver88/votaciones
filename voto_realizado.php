<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Voto realizado</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" type="img/logo.jpg" href="img/logo.jpg">

    <style>
        body {
            background-color: #343a40;
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Ajustar al tamaño de la pantalla */
            font-size: 16px;
        }
        .container{

            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card-container {
            background-color: #212529;
            border-radius: 10px;
            padding: 20px;
            width: 80%;
            max-width: 700px;
            text-align: center;
        }
        .logo-container {
            margin-bottom: 20px;
        }
        .logo-container img {
            max-height: 100px;
            width: auto;
            border-radius: 10px;
        }
        .btn-back {
            margin-top: 20px;
            padding: 20px;
            font-size: 25px;

        }
        p {
            font-size: 18px;
            margin-bottom: 10px;

        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card-container">
            <div class="logo-container">
                <img src="img/logo.jpg" alt="Logo de la empresa" class="img-fluid">
            </div>
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
                $hora_voto = new DateTime($fila['fecha_hora_registro']);
                echo "<p>Hora del voto: " . $hora_voto->format('d/m/Y H:i:s') . "</p>";
            } else {
                echo "<p>No se pudo obtener la hora del voto.</p>";
            }

            // Cerrar la conexión y la sentencia preparada
            $stmt->close();
            $conexion->close();
            ?>
          
            <a href="index.php" class="btn btn-primary btn-back">Volver al inicio</a>
        </div>
    </div>
</body>
</html>
