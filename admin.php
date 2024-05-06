<?php
session_start();

// Verificar si el usuario no está autenticado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Redirigir al inicio de sesión si no está autenticado
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel de Administración - Votos por Clase</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Panel de Administración - Votos por Clase</h2>

    <canvas id="votosChart" width="400" height="200"></canvas>

    <script>
        // Obtener datos de PHP (votos por clase)
        var totalClase1 = <?php echo obtenerTotalClase(1); ?>;
        var totalClase2 = <?php echo obtenerTotalClase(2); ?>;

        // Configurar la gráfica con Chart.js
        var ctx = document.getElementById('votosChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Clase 1', 'Clase 2'],
                datasets: [{
                    label: 'Número de Votos por Clase',
                    data: [totalClase1, totalClase2],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)', // Color para Clase 1
                        'rgba(54, 162, 235, 0.5)'  // Color para Clase 2
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',  // Borde para Clase 1
                        'rgba(54, 162, 235, 1)'   // Borde para Clase 2
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        // Función para obtener el total de votantes por clase desde PHP
        function obtenerTotalClase(clase) {
            var total = 0;
            <?php
            // Conexión a la base de datos y consulta para obtener el total de votantes por clase
            $conexion = new mysqli("localhost", "root", "", "sistema_votacion");
            if ($conexion->connect_error) {
                die("Error en la conexión: " . $conexion->connect_error);
            }
            
            function obtenerTotalVotos($clase) {
                global $conexion;
                $query = "SELECT COUNT(*) AS total FROM votos WHERE clase = $clase";
                $resultado = $conexion->query($query);
                if ($resultado && $resultado->num_rows > 0) {
                    $row = $resultado->fetch_assoc();
                    return $row['total'];
                } else {
                    return 0;
                }
            }
            ?>

            // Obtener el total de votantes para la clase 1 y clase 2
            <?php $totalClase1 = obtenerTotalVotos(1); ?>
            <?php $totalClase2 = obtenerTotalVotos(2); ?>

            // Retornar el total de votantes según la clase especificada
            switch (clase) {
                case 1:
                    total = <?php echo $totalClase1; ?>;
                    break;
                case 2:
                    total = <?php echo $totalClase2; ?>;
                    break;
                default:
                    total = 0;
                    break;
            }

            return total;
        }
    </script>
</body>
</html>