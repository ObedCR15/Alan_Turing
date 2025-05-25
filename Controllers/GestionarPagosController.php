<?php
require_once('../Models/GestionarPagosModel.php');

class GestionarPagosController {
    private $model;

    // Constructor que recibe la conexión PDO y crea el modelo
    public function __construct($pdo) {
        $this->model = new GestionarPagosModel($pdo);
    }

    // Obtener los pagos agrupados por programa
    public function obtenerPagosPorPrograma($id_programa = null) {
        return $this->model->obtenerPagosPorPrograma($id_programa);
    }

    // Obtener todos los programas
    public function obtenerProgramas() {
        return $this->model->obtenerProgramas();
    }

    // Obtener ingresos totales de las pensiones pagadas
    public function obtenerIngresos() {
        return $this->model->obtenerIngresos();
    }

    // Obtener ingresos de pensiones
    public function obtenerIngresosPensiones() {
        return $this->model->obtenerIngresosPensiones();
    }

    // Obtener ingresos de matrículas
    public function obtenerIngresosMatriculas() {
        return $this->model->obtenerIngresosMatriculas();
    }

    // Calcular ingresos totales (pensiones + matriculas)
    public function obtenerIngresoTotal() {
        $ingresos_pensiones = $this->obtenerIngresosPensiones();
        $ingresos_matriculas = $this->obtenerIngresosMatriculas();
        return $ingresos_pensiones + $ingresos_matriculas;
    }

    // Actualizar el estado de una pensión
    public function actualizarEstadoPension($id_estudiante, $id_programa, $numero_cuota, $estado_pension) {
        return $this->model->actualizarEstadoPension($id_estudiante, $id_programa, $numero_cuota, $estado_pension);
    }

    // Obtener las pensiones asociadas a un programa
    public function obtenerPensionesPorPrograma($id_programa) {
        return $this->model->obtenerPensionesPorPrograma($id_programa);
    }
}
?>
