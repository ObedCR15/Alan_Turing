<?php
session_start();

// Verificar si se ha pasado el parámetro 'role' en la URL
if (isset($_GET['role'])) {
    $_SESSION['role'] = $_GET['role']; // Guardamos el rol seleccionado en la sesión
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Pantalla de selección de usuario - Sistema Académico" />
    <meta name="author" content="Neal Napa Fuentes" />
    <title>Bienvenido Alan Turing Institute</title>
    <link href="assets/css/styles.css" rel="stylesheet" />
    <script src="assets/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Selecciona tu rol</h3>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <p class="lead">Bienvenido al Sistema Académico. Selecciona tu rol para continuar:</p>
                                        <div class="row">
                                            <div class="col-12">
                                                <!-- Botón para Administrador -->
                                                <a href="Views/loginAdministrador.php?role=administrador" class="btn btn-danger btn-block mb-3">
                                                    <i class="fas fa-user-shield"></i> Administrador
                                                </a>
                                                 
                                                <!-- Botón para Estudiante -->
                                                <a href="Views/loginEstudiante.php?role=estudiante" class="btn btn-primary btn-block mb-3">
                                                    <i class="fas fa-user-graduate"></i> Estudiante
                                                </a>
                                                 
                                                <!-- Botón para Docente -->
                                                <a href="Views/loginDocente.php?role=docente" class="btn btn-danger btn-block mb-3">
                                                    <i class="fas fa-chalkboard-teacher"></i> Docente
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Scripts necesarios para el funcionamiento -->
    <script src="assets/js/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
