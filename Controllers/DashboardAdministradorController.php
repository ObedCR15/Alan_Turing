<?php
// /Controllers/DashboardAdministradorController.php
require_once('../Models/DashboardAdministradorModel.php');

class DashboardAdministradorController {
    private $adminModel;

    public function __construct() {
        $this->adminModel = new DashboardAdministradorModel();  // Instancia del modelo
    }

    // Método para manejar la autenticación del administrador
    public function login($email, $clave) {
        $admin = $this->adminModel->verificarAdministrador($email, $clave);
        if ($admin) {
            // Si la autenticación es exitosa, guardamos la información del administrador en sesión
            $_SESSION['admin_id'] = $admin['id_administrador'];
            $_SESSION['nombre'] = $admin['nombre'];
            header('Location: /Views/DashboardAdministrador.php');  // Redirige al dashboard
            exit();
        } else {
            // Si la autenticación falla, redirige al formulario de login con un error
            header('Location: /Views/loginAdministrador.php?error=1');
            exit();
        }
    }

    // Método para obtener los datos del dashboard y pasarlos a la vista
    public function obtenerDatosDashboard() {
        // Obtener datos del modelo
        $totalPagos = $this->adminModel->getTotalPagos();
        $totalMatriculas = $this->adminModel->getTotalMatriculas();
        $totalProgramas = $this->adminModel->getTotalProgramas();
        $totalUsuarios = $this->adminModel->getTotalUsuarios();

        // Pasar los datos a la vista
        return [
            'totalPagos' => $totalPagos,
            'totalMatriculas' => $totalMatriculas,
            'totalProgramas' => $totalProgramas,
            'totalUsuarios' => $totalUsuarios
        ];
    }
}
?>
