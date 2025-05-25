<?php
session_start();
require_once '../Models/LoginEstudianteModel.php';

class LoginEstudianteController {
    public function procesarLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validarCampos();
            
            $dni = trim($_POST['dni']);
            $password = $_POST['password'];
            
            $estudiante = LoginEstudianteModel::autenticar($dni, $password);
            
            if ($estudiante) {
                $this->iniciarSesion($estudiante);
                header('Location: ../Views/dashboardEstudiante.php');
                exit();
            }
            
            $_SESSION['error'] = 'DNI o contraseña incorrectos';
            header('Location: ../Views/LoginEstudiante.php');
            exit();
        }
    }
    
    private function validarCampos() {
        if (empty($_POST['dni']) || empty($_POST['password'])) {
            $_SESSION['error'] = "Todos los campos son requeridos";
            header('Location: ../Views/LoginEstudiante.php');
            exit();
        }
        
        if (!preg_match('/^\d{8}$/', $_POST['dni'])) {
            $_SESSION['error'] = 'El DNI debe tener 8 dígitos';
            header('Location: ../Views/LoginEstudiante.php');
            exit();
        }
    }
    
    private function iniciarSesion($datosUsuario) {
        $_SESSION['student_id'] = $datosUsuario['id'];
        $_SESSION['student_name'] = $datosUsuario['nombre'];
        $_SESSION['user_role'] = 'student';
        session_regenerate_id(true);
    }
}

// Ejecutar controlador
$controller = new LoginEstudianteController();
$controller->procesarLogin();
?>