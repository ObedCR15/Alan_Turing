<?php
require_once "../Models/DocenteCambiarClaveModel.php";

class DocenteCambiarClaveController {
    private $model;
    private $error = '';
    private $mensaje = '';

    public function __construct(PDO $pdo) {
        $this->model = new DocenteCambiarClaveModel($pdo);
    }

    public function cambiarClave(array $postData, int $idDocente) {
        $this->error = '';
        $this->mensaje = '';

        $claveActual = trim($postData['clave_actual'] ?? '');
        $nuevaClave = trim($postData['nueva_clave'] ?? '');
        $confirmarClave = trim($postData['confirmar_clave'] ?? '');

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

        $claveHash = $this->model->obtenerClaveActual($idDocente);
        if (!$claveHash) {
            $this->error = "No se encontró el usuario.";
            return false;
        }

        if (!password_verify($claveActual, $claveHash)) {
            $this->error = "La contraseña actual es incorrecta.";
            return false;
        }

        $nuevoHash = password_hash($nuevaClave, PASSWORD_DEFAULT);
        $actualizado = $this->model->actualizarClave($idDocente, $nuevoHash);

        if ($actualizado) {
            $this->mensaje = "Contraseña actualizada exitosamente.";
            return true;
        } else {
            $this->error = "Error al actualizar la contraseña.";
            return false;
        }
    }

    public function getError(): string {
        return $this->error;
    }

    public function getMensaje(): string {
        return $this->mensaje;
    }
}
?>
