<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Votación Clase 1</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background-color: #343a40;
            color: #ffffff;
            font-size: 20px;
            text-align: center;
            padding-top: 50px;
            position: relative;
        }

        .carousel-control-prev, .carousel-control-next {
            background-color: #007bff;
            padding: 15px;
            top: 50%; /* Centrar verticalmente */
            transform: translateY(-50%);
            width: 10%; /* Ancho reducido para las flechas */
            opacity: 0.8; /* Opacidad reducida para las flechas */
            border-radius: 30px
        }

        .carousel-control-prev-icon, .carousel-control-next-icon {
            color: #ffffff;
            font-size: 24px;
        }

        .carousel-item {
            text-align: center;
            padding-left: 5%;
            padding-right: 5%;
        }

        .candidate-image {
            width: 350px; /* Ancho fijo para todas las imágenes */
            height: 350px; /* Altura fija para todas las imágenes */
            object-fit: cover; /* Ajusta la imagen dentro del contenedor sin deformarla */
            border-radius: 10%; /* Borde redondeado para crear un círculo */
            margin-bottom: 20px; /* Margen inferior para separar las imágenes */
            display: block; /* Asegura que la imagen se muestre como bloque */
            margin-left: auto; /* Centra la imagen horizontalmente */
            margin-right: auto; /* Centra la imagen horizontalmente */
        }

        .carousel-inner {
            position: relative;
        }

        .candidate-selector {
            display: inline-block;
            cursor: pointer;
        }

        .candidate-selector input[type="radio"] {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0;
            cursor: pointer;
        }

        .candidate-selector .checkmark {
            position: absolute;
            top: 10px;
            right: 10px;
            height: 30px;
            width: 30px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            display: none;
        }

        .candidate-selector input[type="radio"]:checked + .checkmark {
            display: block;
        }

        .vote-btn {
            margin-top: 30px;
            font-size: 24px;
            padding: 15px;
        }

        /* Estilos personalizados para el modal */
        .modal-content {
            background-color: #000000;
            color: #ffffff;
        }

        .modal-header {
            border-bottom: none;
        }

        

        /* Estilos para el botón de accesibilidad */
        #accessibilityBtn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 20px;
            padding: 15px 20px;
            z-index: 999;
        }

        /* Media query para dispositivos móviles */
        @media (max-width: 768px) {
            .candidate-image {
                width: 250px; /* Reducir el tamaño de la imagen en dispositivos móviles */
                height: 250px; /* Reducir el tamaño de la imagen en dispositivos móviles */
            }
            
            /* Estilos para el botón "Salir" en dispositivos móviles */
            .btn-salir {
                font-size: 18px;
                padding: 10px;
                position: absolute;
                top: 20px; /* Posiciona el botón 20px desde la parte superior */
                right: 20px; /* Posiciona el botón 20px desde el borde derecho */
            }
        }

        /* Estilos para el botón "Salir" en pantallas más grandes */
        @media (min-width: 768px) {
            .btn-salir {
                font-size: 18px;
                padding: 10px;
                position: absolute;
                top: 10px; /* Posiciona el botón 10px desde la parte superior */
                right: 10px; /* Posiciona el botón 10px desde el borde derecho */
            }
        }

               /* Estilos para el botón "Salir" */
            #btnSalir {
            position: fixed;
            top: 20px;
            right: 20px;
            font-size: 24px;
            padding: 15px 30px;
            z-index: 9999;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>Votación Clase 2 - Candidatos</h2>
                <br>
                <br>
                <form id="votacionForm" action="procesar_voto.php" method="POST">
                    <input type="hidden" name="usuario_id" value="<?= isset($_GET['usuario_id']) ? $_GET['usuario_id'] : ''; ?>">

                    <div id="candidateCarousel" class="carousel slide" data-ride="carousel" data-interval="false">
                        <div class="carousel-inner">
                            <?php
                            $conexion = new mysqli("localhost", "root", "", "x");

                            // Obtener los candidatos de la clase 2 (clase = 2)
                            $query = "SELECT id, nombre, nombre_foto FROM candidatos WHERE id_categoria = 2";
                            $resultado = $conexion->query($query);

                            if ($resultado && $resultado->num_rows > 0) {
                                $active = "active";
                                while ($candidato = $resultado->fetch_assoc()) {
                                    echo '<div class="carousel-item ' . $active . '">';
                                    echo '<img src="' . $candidato['nombre_foto'] . '" class="d-block mx-auto candidate-image" alt="' . htmlspecialchars($candidato['nombre']) . '" data-candidato-id="' . $candidato['id'] . '">';
                                    echo '<h3>' . htmlspecialchars($candidato['nombre']) . '</h3>';
                                    echo '</div>';
                                    $active = "";
                                }
                            } else {
                                echo "No se encontraron candidatos para mostrar.";
                            }
                            ?>
                        </div>
                        <a class="carousel-control-prev" href="#candidateCarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Anterior</span>
                            
                        </a>
                        <a class="carousel-control-next" href="#candidateCarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Siguiente</span>
                           
                        </a>
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

                    // Captura el evento cuando se hace clic en la imagen del candidato
                    $('.candidate-image').on('click', function() {
                        // Al hacer clic en una imagen de candidato, actualiza el ID del candidato seleccionado
                        candidatoSeleccionadoId = $(this).data('candidato-id');
                    });

                    // Navegación con controles previos y siguientes en el carrusel
                    $('#candidateCarousel').on('slide.bs.carousel', function (e) {
                        var candidatoActivo = $(e.relatedTarget).find('.candidate-image');
                        candidatoSeleccionadoId = candidatoActivo.data('candidato-id');
                    });

                    // Preselecciona el primer candidato cuando se carga la página por primera vez
                    var primerCandidato = $('.candidate-image').first();
                    if (primerCandidato.length > 0) {
                        candidatoSeleccionadoId = primerCandidato.data('candidato-id');
                        // Muestra visualmente la selección del primer candidato (opcional)
                        primerCandidato.addClass('candidato-seleccionado');
                    }

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