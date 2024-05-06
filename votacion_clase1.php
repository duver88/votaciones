<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Votación Clase 1</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" type="img/logo.jpg" href="img/logo.jpg">

    <style>
        body {
            background-color: #343a40;
            color: #ffffff;
            font-size: 20px;
            text-align: center;
            padding-top: 50px;
            position: relative;
        }

        .candidate-image {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 10%;
            margin-bottom: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .candidate-selector {
            position: relative;
            display: inline-block;
            text-align: center;
        }

        .candidate-selector input[type="radio"] {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
            height: 100%;
            width: 100%;
        }

        .candidate-selector .checkmark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            height: 200px;
            width: 200px;
            border: none;
            border-radius: 0;
            display: none;
        }

        .candidate-selector .checkmark::before,
        .candidate-selector .checkmark::after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(255, 0, 0, 0.7);
            width: 100%;
            height: 40%;
        }

        .candidate-selector .checkmark::before {
            transform: translate(-50%, -50%) rotate(45deg);
        }

        .candidate-selector .checkmark::after {
            transform: translate(-50%, -50%) rotate(-45deg);
        }

        .candidate-selector input[type="radio"]:checked + .checkmark {
            display: block;
        }

        /* Estilos para el botón "Salir" */
        #btnSalir {
            position: fixed;
            top: 20px;
            right: 20px;
            font-size: 24px;
            padding: 15px 15px;
            z-index: 9999;
        }

        .modal-content {
            background-color: #222222; /* Fondo oscuro */
            color: #ffffff; /* Texto blanco */
            font-size: 25px;
        }

        /* Estilos para la X (cerrar) del modal */
        .modal-content .close {
            color: #ff0000; /* Color rojo */
            font-size: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
            <div class="card-header">
            <img src="img/logo.jpg" alt="Logo" class="img-fluid" style="max-height: 150px; width: auto; border-radius: 25px;">
            <br>
            </div>
                <h2>Votación Clase 1 - Candidatos</h2>
                <br>
                <br>
                <form id="votacionForm" action="procesar_voto.php" method="POST">
                    <input type="hidden" name="usuario_id" value="<?= isset($_GET['usuario_id']) ? $_GET['usuario_id'] : ''; ?>">

                    <div class="row">
                        <?php
                        $conexion = new mysqli("localhost", "root", "", "x");

                        // Obtener los candidatos de la clase 2 (clase = 2)
                        $query = "SELECT id, nombre, nombre_foto FROM candidatos WHERE id_categoria = 1";
                        $resultado = $conexion->query($query);

                        if ($resultado && $resultado->num_rows > 0) {
                            while ($candidato = $resultado->fetch_assoc()) {
                                echo '<div class="col-md-4 mb-4">';
                                echo '<div class="candidate-selector">';
                                echo '<img src="' . $candidato['nombre_foto'] . '" class="d-block mx-auto candidate-image" alt="' . htmlspecialchars($candidato['nombre']) . '" data-candidato-id="' . $candidato['id'] . '">';
                                echo '<input type="radio" name="candidato_seleccionado" value="' . $candidato['id'] . '" id="candidato_' . $candidato['id'] . '">';
                                echo '<label for="candidato_' . $candidato['id'] . '" class="checkmark"></label>';
                                echo '<h3>' . htmlspecialchars($candidato['nombre']) . '</h3>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo "No se encontraron candidatos para mostrar.";
                        }
                        ?>
                    </div>

                    <button type="button" class="btn btn-primary btn-block vote-btn" data-toggle="modal" data-target="#confirmModal">Votar</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Confirmación modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmar Voto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que quieres votar por este candidato?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="confirmVotoBtn">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón "Salir" -->
    <button id="btnSalir" class="btn btn-danger" onclick="window.location.href = 'index.php';">Salir</button>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            var candidatoSeleccionadoId = null;

            // Captura el evento cuando se selecciona un candidato
            $('input[name="candidato_seleccionado"]').on('change', function() {
                candidatoSeleccionadoId = $(this).val();
            });

            // Captura el evento cuando se hace clic en el botón de "Votar"
            $('.vote-btn').on('click', function() {
                // Verifica si se ha seleccionado un candidato
                if (candidatoSeleccionadoId !== null) {
                    // Obtiene el nombre del candidato seleccionado
                    var nombreCandidato = $(`.candidate-image[data-candidato-id="${candidatoSeleccionadoId}"]`).siblings('h3').text();

                    // Actualiza el contenido de la ventana modal con el nombre del candidato
                    $('#confirmModal .modal-body').text('¿Estás seguro de que quieres votar por ' + nombreCandidato + '?');

                    // Asigna el ID del candidato seleccionado al botón de confirmación dentro de la ventana modal
                    $('#confirmVotoBtn').data('candidato-id', candidatoSeleccionadoId);
                } else {
                    // Si no se ha seleccionado ningún candidato
                    $('#confirmModal .modal-body').text('Por favor, selecciona un candidato para votar.');
                }
            });

            // Captura el evento cuando se confirma el voto desde la ventana modal
            $('#confirmVotoBtn').on('click', function() {
                var candidatoId = $(this).data('candidato-id');

                // Agrega el ID del candidato al formulario antes de enviar
                $('#votacionForm').append('<input type="hidden" name="candidato_id" value="' + candidatoId + '">');

                // Envía el formulario de votación
                $('#votacionForm').submit();
            });
        });
    </script>
</body>
</html>
