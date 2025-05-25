<?php
session_start();
require_once '../Models/LoginDocenteModel.php';

class LoginDocenteController {
    public function procesarLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validarCampos();
            
            $dni = trim($_POST['dni']);
            $password = $_POST['password'];
            
            $docente = LoginDocenteModel::autenticar($dni, $password);
            
            if ($docente) {
                $this->iniciarSesion($docente);
                header('Location: ../Views/dashboardDocente.php');
                exit();
            }
            
            $_SESSION['error'] = 'DNI o contraseña incorrectos';
            header('Location: ../Views/LoginDocente.php');
            exit();
        }
    }
    
    private function validarCampos() {
        if (empty($_POST['dni']) || empty($_POST['password'])) {
            $_SESSION['error'] = "Todos los campos son requeridos";
            header('Location: ../Views/LoginDocente.php');
            exit();
        }
        
        if (!preg_match('/^\d{8}$/', $_POST['dni'])) {
            $_SESSION['error'] = 'El DNI debe tener 8 dígitos';
            header('Location: ../Views/LoginDocente.php');
            exit();
        }
    }
    
    private function iniciarSesion($datosUsuario) {
        $_SESSION['docente_id'] = $datosUsuario['id'];
        $_SESSION['docente_name'] = $datosUsuario['nombre'];
        $_SESSION['user_role'] = 'docente';
        session_regenerate_id(true);
    }
}

// Ejecutar controlador
$controller = new LoginDocenteController();
$controller->procesarLogin();
?>
