<?php
session_start();
require_once '../Models/LoginAdministradorModel.php';  // Incluir el modelo

class LoginAdministradorController {
    // Método para procesar el login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Recoger los datos del formulario
            $dni = $_POST['dni'];
            $password = $_POST['password'];

            // Validar las credenciales usando el modelo
            $admin = LoginAdministradorModel::login($dni, $password);

            if ($admin) {
                // Si las credenciales son correctas, almacenar los datos en la sesión
                $_SESSION['admin_id'] = $admin['id_administrador'];
                $_SESSION['dni'] = $admin['dni'];

                // Redirigir al dashboard del administrador
                header('Location: ../Views/dashboardAdministrador.php');
                exit();
            } else {
                // Si las credenciales son incorrectas, mostrar un mensaje de error
                $_SESSION['error'] = 'DNI o contraseña incorrectos';
                header('Location: ../Views/loginAdministrador.php');  // Volver al formulario de login
                exit();
            }
        }
    }
}

// Crear una instancia del controlador y procesar la acción
$controller = new LoginAdministradorController();
if (isset($_GET['action']) && $_GET['action'] == 'login') {
    $controller->login();  // Llamar al método de login
}
?>
