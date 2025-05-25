<?php
require_once('../Models/GestionarPensionModel.php');

class GestionarPensionController {
    private $pensionModel;

    public function __construct($pdo) {
        $this->pensionModel = new GestionarPensionModel($pdo);
    }

    // Obtener las pensiones de un programa
    public function obtenerPensionesPorPrograma($id_programa) {
        return $this->pensionModel->obtenerPensionesPorPrograma($id_programa);
    }

    // Obtener la cantidad de cuotas de un programa
    public function obtenerCantidadCuotas($id_programa) {
        return $this->pensionModel->obtenerCantidadCuotas($id_programa);
    }

    // Crear las pensiones si no existen
    public function crearPensionesSiNoExisten($id_estudiante, $id_programa, $cantidad_cuotas, $monto) {
        $pensionesExistentes = $this->obtenerPensionesPorPrograma($id_programa);
        if (count($pensionesExistentes) == 0) {
            $this->pensionModel->crearPensiones($id_programa, $id_estudiante, $cantidad_cuotas, $monto);
        }
    }

    // Actualizar el estado de una pensión
    public function actualizarEstadoPension($id_estudiante, $id_programa, $numero_cuota, $estado_pension) {
        return $this->pensionModel->actualizarEstadoPension($id_estudiante, $id_programa, $numero_cuota, $estado_pension);
    }

    // Guardar cambios (en este caso, lo que hará es redirigir a GestionarPagos)
    public function guardarCambios($id_estudiante, $id_programa) {
        // Puedes aquí agregar cualquier lógica adicional si es necesario (p.ej., actualización de base de datos)
        // Aquí simplemente redirigimos a la página de "GestionarPagos"
        $_SESSION['mensaje'] = "✅ Cambios guardados correctamente";
        header("Location: GestionarPagos.php?id_estudiante=$id_estudiante&id_programa=$id_programa");
        exit();
    }
}
?>
