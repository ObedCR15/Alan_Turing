<?php
session_start();
require_once '../Models/LoginAdministradorCambiarClaveModel.php';  // Incluir el modelo

class LoginAdministradorCambiarClaveController {
    private $model;
    private $error = '';
    private $mensaje = '';

    public function __construct(PDO $pdo) {
        $this->model = new LoginAdministradorCambiarClaveModel($pdo);
    }

    // Método para cambiar la contraseña
    public function cambiarClave(array $postData, int $idAdministrador) {
        $this->error = '';
        $this->mensaje = '';

        // Recoger los datos
        $claveActual = trim($postData['clave_actual'] ?? '');
        $nuevaClave = trim($postData['nueva_clave'] ?? '');
        $confirmarClave = trim($postData['confirmar_clave'] ?? '');

        // Validaciones
        if (empty($claveActual) || empty($nuevaClave) || empty($confirmarClave)) {
            $this->error = "Todos los campos son obligatorios.";
            return false;
        }

        if (strlen($nuevaClave) < 4 || strlen($nuevaClave) > 20) {
            $this->error = "La nueva contraseña debe tener entre 4 y 20 caracteres.";
            return false;
        }

        if ($nuevaClave !== $confirmarClave) {
            $this->error = "La nueva contraseña y la confirmación no coinciden.";
            return false;
        }

        // Verificar la contraseña actual
        $claveHash = $this->model->obtenerClaveActual($idAdministrador);
        if (!$claveHash) {
            $this->error = "No se encontró el administrador.";
            return false;
        }

        if (!password_verify($claveActual, $claveHash)) {
            $this->error = "La contraseña actual es incorrecta.";
            return false;
        }

        // Generar la nueva clave encriptada
        $nuevoHash = password_hash($nuevaClave, PASSWORD_DEFAULT);
        $actualizado = $this->model->actualizarClave($idAdministrador, $nuevoHash);

        if ($actualizado) {
            $this->mensaje = "Contraseña actualizada exitosamente.";
            return true;
        } else {
            $this->error = "Error al actualizar la contraseña.";
            return false;
        }
    }

    // Métodos para obtener errores y mensajes
    public function getError(): string {
        return $this->error;
    }

    public function getMensaje(): string {
        return $this->mensaje;
    }
}
?>
